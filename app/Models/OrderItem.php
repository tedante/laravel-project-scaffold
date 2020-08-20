<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
  protected $fillable = [
    'order_id',
    'material_id',
    'qty',
    'notes',
    'discount_percentage',
    'special_sale_percentage',
    'price_after_discount',
    'price_after_sale',
    'price',
    'final_price',
  ];

  protected $appends = [
    'discount_percentage_text',
    'special_sale_percentage_text'
  ];

  protected $casts = [
    'discount_percentage' => 'double',
    'special_sale_percentage' => 'double',
    'price_after_discount' => 'double',
    'price_after_sale' => 'double',
    'price' => 'double',
    'final_price' => 'double',
  ];

  public function order() {
    return $this->belongsTo(Order::class);
  }

  public function getDiscountPercentageTextAttribute(){
    return (string) ($this->discount_percentage * 100) .'%';
  }

  public function getSpecialSalePercentageTextAttribute(){
    return (string) ($this->special_sale_percentage * 100) .'%';
  }

  public function material(){
    return $this->belongsTo(Material::class);
  }
}
