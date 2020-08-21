<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
  protected $fillable = [
    'name', 'meta'
  ];
  
  protected $casts = [
    'meta' => 'array',
  ];

  protected $hidden = [
    'created_at', 'updated_at'
  ];

  public function cities()
  {
    return $this->hasMany(City::class);
  }

  public function districts()
  {
    return $this->hasManyThrough(District::class, City::class);
  }

}
