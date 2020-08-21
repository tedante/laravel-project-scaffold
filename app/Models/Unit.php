<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
  
  protected $fillable = [
    'name'
  ];

  protected $hidden = [
    'created_at', 'updated_at'
  ];

  public function materials() {
    return $this->hasMany(Material::class);
  }

}
