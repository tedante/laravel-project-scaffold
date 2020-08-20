<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\UnprocessEntityException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class BaseController extends Controller
{
    protected $model;

    protected $validation;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requestQuery = request()->query();

        $validation = Validator::make($requestQuery, [
          'page' => 'integer|min:1',
          'perPage' => 'integer|min:1',
          'order-by' => 'required_with:sort-by|in:asc,desc',
          'min' => 'regex:/^\d+(\.\d{1,2})?$/',
          'max' => 'regex:/^\d+(\.\d{1,2})?$/'
        ]);
    
        if($validation->fails()) {
            return response()->json([
                "message" => $validation->messages()
            ], 422);
        }

        $limit = $requestQuery['perPage'] ?? 25;
        
        $model = new $this->model();

        $column = $model->getConnection()
                        ->getSchemaBuilder()
                        ->getColumnListing(
                            $model->getTable()
                        );

        $data = $model::query();

        if (isset($requestQuery['filters'])) {
            foreach($requestQuery['filters'] as $key => $value) {
                $filters = explode(",", $value);
                
                if (in_array($key, $column)) {
                    foreach ($filters as $item) {
                        $data = $data->where($key, 'like', "%".$item."%");
                    }
                }
            }
        }

        if(isset($requestQuery['sort-by'])) {
            if (in_array($requestQuery['sort-by'], $column)) {
                $data = $data->orderBy($requestQuery['sort-by'], $requestQuery['order-by']);
            }
        }
        
        $data = $data->paginate($limit);
    
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestJson = $request->json()->all();

        if (isset($this->validation)) {
            $validation = Validator::make($requestJson, $this->validation);
    
            if($validation->fails()) {
                return response()->json([
                    "message" => $validation->messages()
                ], 422);
            }

            $model = new $this->model();

            try{
                DB::beginTransaction();
    
                $data = $model->create($requestJson);

                DB::commit();
                
                $data = $model::find($data->id);
        
                return response()->json($data);
            } catch (Exception $e) {
              DB::rollback();
        
              throw $e;
            }
        }

        return response()->json([
            "message" => "Validation is error"
        ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
