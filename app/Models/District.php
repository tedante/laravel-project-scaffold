<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
  protected $fillable = [
    'name', 'city_id', 'meta'
  ];

  protected $casts = [
    'meta' => 'array',
  ];

  protected $hidden = [
    'created_at', 'updated_at'
  ];

  public function city()
  {
      return $this->belongsTo(City::class);
  }

  public function villages()
  {
      return $this->hasMany(Village::class);
  }

  public function getCityNameAttribute()
  {
      return $this->city->name;
  }

  public function getProvinceNameAttribute()
  {
      return $this->city->province->name;
  }
}
