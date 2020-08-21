<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialCategoryPartner extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'material_id',
    'category_partner_id',
    'discount',
  ];

  public function material() {
    return $this->belongsTo(Material::class);
  }

  public function categoryPartner() {
    return $this->belongsTo(CategoryPartner::class);
  }
}
