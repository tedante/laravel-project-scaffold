<?php

namespace App\Http\Controllers;

use App\Exceptions\UnprocessEntityException;
use App\Libraries\Order;
use App\Models\Order as ModelsOrder;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{

  public function index(Request $request)
  {
    $partner = Auth::user()->partner;
    $requestQuery = $request->query();
    $statuses = array_keys(ModelsOrder::STATUS);
    $statuses[] = 'all';

    $validate = Validator::make($requestQuery, [
      'status' => [
        Rule::in($statuses)
      ],
      'count_only' => 'boolean',
      'tracking_count_only' => 'boolean',
      'order_number' => 'string',
      'receiver_name' => 'string',
      'sales_order' => 'string',
      'user_id' => 'integer',
      'page' => 'integer|min:1',
      'perPage' => 'integer|min:1',
      'sortBy' => 'in:prices,name,code,created_at,updated_at',
      'dir' => 'required_with:sortBy|in:asc,desc',
      'start_date' => 'date|before:tomorrow',
      'end_date' => 'date|before:start_date',
    ]);
    
    if($validate->fails()) {
      throw new ValidationException($validate);
    }

    $limit = $requestQuery['perPage'] ?? 25;

    $order = ModelsOrder::with('user');
    if($partner){
      $order = $order->where('user_id', $partner->user_id);
    }

    if(isset($requestQuery['tracking_count_only']) && $requestQuery['tracking_count_only']){
      $order = $order->where('status', '!=', ModelsOrder::STATUS['received']);
      $order = $order->where('status', '!=', ModelsOrder::STATUS['cancel']);
      $order = $order->where('status', '!=', ModelsOrder::STATUS['decline']);
      $order = $order->count();

      return response()->json([
        'count' => $order
      ]);
    }

    if(isset($requestQuery['count_only']) && $requestQuery['count_only']){
      $order = $order->count();

      return response()->json([
        'count' => $order
      ]);
    }
    if(isset($requestQuery['status']) && $requestQuery['status'] != 'all'){
      $status = ModelsOrder::STATUS[$requestQuery['status']];
      $order = $order->where('status', $status);
    }
    if(isset($requestQuery['order_number'])){
      $order = $order->where('order_number', 'LIKE', '%'.$requestQuery['order_number'].'%');
    }
    if(isset($requestQuery['sales_order'])){
      $order = $order->where('sales_order', 'LIKE', '%'.$requestQuery['sales_order'].'%');
    }
    if(isset($requestQuery['receiver_name'])){
      $order = $order->where('receiver_name', 'LIKE', '%'.$requestQuery['receiver_name'].'%');
    }
    if(isset($requestQuery['customer_name'])){
      $customerName = $requestQuery['customer_name'];
      $order = $order->whereHas('user', function($q) use ($customerName) {
          $q->where('name', 'LIKE', '%'.$customerName.'%');
      });
    }
    if(isset($requestQuery['user_id'])){
      $order = $order->where('user_id', $requestQuery['user_id']);
    }
    if(isset($requestQuery['sortBy'])) {
      $order = $order->orderBy($requestQuery['sortBy'], $requestQuery['dir']);
    }
    
    $order = $order->paginate($limit);

    return response()->json($order);
  }

  public function store(Request $request)
  {
    $requestJson = $request->json()->all();
    $validate = Validator::make($requestJson, [
      'user_id' => 'required|integer',
      'address_id' => 'required|integer|exists:addresses,id',
      'storage_location_id' => 'integer|exists:storage_locations,id|nullable'
    ]);

    if($validate->fails()) throw new ValidationException($validate);

    $user = User::where('role', 'partner')->where('id', $requestJson['user_id'])->first();
    if(!$user) throw new ModelNotFoundException('User Not Found');
    
    $order = new Order($user);
    $order = $order->create($requestJson);
    $order = ModelsOrder::with(['user', 'storage_location', 'history', 'items'])->find($order->id);

    return response()->json($order);
  }

  public function show($id)
  {
    $user = Auth::user();
    $partner = $user->partner;

    $order = ModelsOrder::with([
      'user', 
      'user.partner',
      'storage_location',
      'items', 
      'items.material', 
      'items.material.material_type', 
      'items.material.category',
      'items.material.unit',
      'history',
    ])->find($id);
    if(!$order) throw new ModelNotFoundException('Order Not Found');
    
    if($partner && $user->id != $order->user_id){
      throw new UnprocessEntityException('This order not belongs to you');
    }

    return response()->json($order);
  }

  public function update(Request $request, $id)
  {
    $requestJson = $request->json()->all();
    $validate = Validator::make($requestJson, [
      'storage_location_id' => 'integer|exists:storage_locations,id',
      'notes' => 'string|max:255',
      'payment_receipt' => 'string|max:255',
      'sales_order' => 'string|max:255|nullable',
      'expedition_name' => 'string|max:255|nullable',
      'delivery_type' => 'string|max:255|nullable',
      'receipt_number' => 'string|max:255|nullable',
      'status' => [ Rule::in(array_keys(ModelsOrder::STATUS)) ],
      'payment_method' => [ Rule::in(array_keys(ModelsOrder::PAYMENT_METHOD)) ],
      'items.*.material_id' => 'integer',
      'items.*.notes' => 'string|max:255',
      'items.*.qty' => 'integer|min:0|max:9999',
    ]);

    if($validate->fails()) throw new ValidationException($validate);
    
    $modelsOrder = ModelsOrder::with([
      'user', 
      'user.partner',
      'storage_location',
      'items', 
      'items.material', 
      'items.material.material_type', 
      'items.material.category',
      'items.material.unit',
      'history'
    ])->find($id); 
    if (!$modelsOrder) {
      throw new ModelNotFoundException('Order Not Found');
    }

    $user = Auth::user();
    $order = new Order($user, $modelsOrder);
    $order = $order->update($requestJson);

    $modelsOrder->refresh();

    return response()->json($modelsOrder);
  }

  public function destroy($id)
  {
      //
  }

  public function deleteItem($orderId, $itemId){
    $orderItem = OrderItem::where('order_id', $orderId)->where('material_id', $itemId)->first();
    if(!$orderItem) throw new ModelNotFoundException('Order Item Not Found');

    $user = Auth::user();

    if($user->partner && $user->id != $orderItem->order->user_id){
      throw new UnprocessEntityException('You can\'t delete this item');
    }

    $orderItem->delete();
    $orderModels = ModelsOrder::find($orderId);
    
    $order = new Order($user, $orderModels);
    $order->recalculate();
    
    return response()->json([
      'success' => true
    ]);
  }

  public function getStatus(Request $request){
    $query = $request->query();

    $validate = Validator::make($query, [
      'start_date' => 'date|before:tomorrow',
      'end_date' => 'date|before:start_date',
    ]);

    $statuses = ModelsOrder::STATUS;
    $partner = Auth::user()->partner;
    
    if(isset($query['only_statuses']) && $query['only_statuses'] == 'true'){
      $data = [
        [
          'key' => 'all',
          'status' => 'All'
        ]
      ];
      foreach($statuses as $key => $status) {
        $data[] = [
          'key' => $key,
          'status' => $status
        ];
      }

      return response()->json($data);
    }

    $countAll = new ModelsOrder;
    if($partner){
      $countAll = $countAll->where('user_id', $partner->user_id);
    }

    if(isset($query['start_date']) && isset($query['end_date'])) {
      $countAll = $countAll->whereBetween('created_at', [$query['start_date'], $query['end_date']]);
    }

    $countAll = $countAll->count();

    $data = [
      [
        'key' => 'all',
        'status' => 'All',
        'count_orders' => $countAll
      ]
    ];
    foreach($statuses as $key => $status) {
      $order = ModelsOrder::where('status', $status);

      if($partner){
        $order = $order->where('user_id', $partner->user_id);
      }
      
      if(isset($query['start_date']) && isset($query['end_date'])) {
        $order = $order->whereBetween('created_at', [$query['start_date'], $query['end_date']]);
      }
      
      $countOrders = $order->count();
      $data[] = [
        'key' => $key,
        'status' => $status,
        'count_orders' => $countOrders
      ];
    }

    return response()->json($data);
  }
}