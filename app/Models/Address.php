<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'partner_id',
    'village_id',
    'country_name',
    'name_address',
    'recipient_name',
    'post_code',
    'address',
    'is_primary',
    'is_active'
  ];

  protected $casts = [
    'is_primary' => 'boolean',
    'is_active' => 'boolean'
  ];

  protected $appends = ['village_name', 'district_name', 'city_name', 'province_name'];

  protected $hidden = [
    'created_at', 'updated_at', 'deleted_at'
  ];

  public function partner() {
    return $this->belongsTo(Partner::class);
  }

  public function village() {
    return $this->belongsTo(Village::class);
  }

  public function getVillageNameAttribute()
  {
      return $this->village->name;
  }

  public function getDistrictNameAttribute()
  {
      return $this->village->district->name;
  }

  public function getCityNameAttribute()
  {
      return $this->village->district->city->name;
  }

  public function getProvinceNameAttribute()
  {
      return $this->village->district->city->province->name;
  }
  
}
