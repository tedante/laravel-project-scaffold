<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

  protected $fillable = [
    'name', 'meta', 'province_id'
  ];

  protected $casts = [
    'meta' => 'array',
  ];

  protected $hidden = [
    'created_at', 'updated_at'
  ];

  public function province()
  {
    return $this->belongsTo(Province::class, 'province_id');
  }

  public function districts()
  {
    return $this->hasMany(District::class, 'city_id');
  }

  public function villages()
  {
    return $this->hasManyThrough(Village::class, District::class);
  }

  public function getProvinceNameAttribute()
  {
    return $this->province->name;
  }
}
