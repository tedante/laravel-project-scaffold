<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
  protected $casts = [
    'meta' => 'array',
  ];

  protected $hidden = [
    'created_at', 'updated_at'
  ];

  public function district()
  {
      return $this->belongsTo(District::class);
  }

  public function getDistrictNameAttribute()
  {
      return $this->district->name;
  }

  public function getCityNameAttribute()
  {
      return $this->district->city->name;
  }

  public function getProvinceNameAttribute()
  {
      return $this->district->city->province->name;
  }
}
