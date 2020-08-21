<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  public const STATUS = [
    'purchase_request' => 'Purchase Request',
    'in_process' => 'In Process',
    'edit_order' => 'Edit Order',
    'purchase_order' => 'Purchase Order',
    'payment_process' => 'Payment Process',
    'payment_received' => 'Payment Received',
    'on_delivery' => 'On Delivery',
    'received' => 'Received',
    'cancel' => 'Cancel',
    'decline' => 'Decline'
  ];

  public const PAYMENT_METHOD = [
    'credit_limit' => 'Credit Limit',
    'cash_before_delivery' => 'Cash Before Delivery'
  ];

  protected $fillable = [
    'order_number',
    'user_id',
    'address_name',
    'address',
    'postal_code',
    'village',
    'district',
    'city',
    'province',
    'country',
    'receiver_name',
    'phone_number',
    'storage_location_id',
    'status',
    'sales_order',
    'expedition_name',
    'receipt_number',
    'delivery_type',
    'payment_method',
    'payment_receipt',
    'subtotal_amount',
    'total_amount',
    'discount_partner',
    'discount_partner_percentage',
    'additional_discount'
  ];

  protected $appends = [
    'discount_partner_percentage_text',
    'last_history'
  ];

  protected $casts = [
    'discount_partner_percentage' => 'double'
  ];

  public function items() {
    return $this->hasMany(OrderItem::class);
  }

  public function getDiscountPartnerAttribute($value){
    return round($value);
  }

  public function getSubtotalAttribute($value){
    return round($value);
  }

  public function getTotalAmountAttribute($value){
    return round($value);
  }

  public function history() {
    return $this->hasMany(OrderHistory::class)->orderBy('created_at', 'DESC')->orderBy('id', 'DESC');
  }

  public function getLastHistoryAttribute() {
    return $this->history->sortByDesc('created_at')->sortByDesc('id')->first();
  }

  public function getDiscountPartnerPercentageTextAttribute(){
    return (string) ($this->discount_partner_percentage * 100) .'%';
  }

  public function user() {
    return $this->belongsTo(User::class);
  }

  public function storage_location() {
    return $this->belongsTo(StorageLocation::class);
  }

}
