<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageLocation extends Model
{
  
  protected $fillable = [
    'city_id',
    'code',
    'name',
    'address',
    'email',
    'maps_link',
  ];

  protected $hidden = [
    
  ];

  protected $appends = [
    'city_name'
  ];

  public function materials() {
    return $this->belongsToMany(Material::class)
                ->using(MaterialStorageLocation::class)
                ->withPivot([
                  'total_stock'
                ]);
  }

  public function city() {
    return $this->belongsTo(City::class);
  }

  public function getCityNameAttribute() {
    return $this->city->name;
  }

}
