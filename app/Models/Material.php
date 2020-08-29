<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
  
  protected $fillable = [
    'unit_id',
    'category_id',
    'material_type_id',
    'material_group',
    'name',
    'code',
    'excerpt',
    'description',
    'document_link',
    'plant',
    'prices',
    'discount_percentage',
    'is_active'
  ];

  protected $casts = [
    'is_active' => 'boolean',
    'prices' => 'string',
    'discount_percentage' => 'string',
  ];
  
  public function getAllRelation() {
    // return name of all relation function 
    return [
      'unit',
      'category',
      'material_type'
    ];
  }

  public function unit() {
    return $this->belongsTo(Unit::class);
  }

  public function getDiscountPercentageTextAttribute() {
    return ($this->discount_percentage * 100) . '%' ?? null;
  }

  public function category() {
    return $this->belongsTo(Category::class);
  }

  public function getPricesAttribute($value){
    return round($value);
  }

  public function material_type(){
    return $this->belongsTo(MaterialType::class);
  }

  public function storage_locations() {
    return $this->belongsToMany(StorageLocation::class)
                ->using(MaterialStorageLocation::class)
                ->withPivot([
                  'total_stock'
                ]);
  }

  public function images() {
    return $this->hasMany(MaterialImage::class);
  }

  public function getFinalPriceAttribute() {
    $specialSale = $this->special_sale->discount_percentage ?? 0;

    return round($this->prices * (1 - $specialSale));
  }

  public function special_sale() {
    return $this->hasOne(SaleProduct::class);
  }

  public function purchase(){
    return $this->hasOne(PurchaseMaterial::class);
  }
  
  public function materialCategoryPartner() {
    return $this->hasOne(MaterialCategoryPartner::class);
  }

}
