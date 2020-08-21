<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialType extends Model
{
  
  protected $fillable = [
    'code',
    'name'
  ];

  protected $hidden = [
    'updated_at', 'created_at'
  ];

  public function materials() {
    return $this->hasMany(Material::class);
  }

}
