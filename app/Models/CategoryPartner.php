<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryPartner extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'name',
    'description'
  ];

  public function partner() {
    return $this->hasMany(Partner::class);
  }

  public function materialCategoryPartner() {
    return $this->hasMany(MaterialCategoryPartner::class);
  }

  public function materials(){
    return $this->belongsToMany(Material::class, MaterialCategoryPartner::class);
  }
}
