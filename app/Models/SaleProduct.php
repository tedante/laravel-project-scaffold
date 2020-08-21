<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleProduct extends Model
{
  protected $fillable = [
    'sale_id', 'material_id', 'discount_percentage'
  ];

  protected $hidden = [
    'created_at', 'updated_at'
  ];

  protected $appends = [
    'discount_percentage_text'
  ];

  protected $casts = [
    'discount_percentage' => 'double'
  ];

  public function material() {
    return $this->belongsTo(Material::class);
  }

  public function getDiscountPercentageTextAttribute() {
    $result = null;
    if($this->discount_percentage) {
      $result = (string) ($this->discount_percentage * 100) . '%';
    }
    
    return $result;
  }

}
