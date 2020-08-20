<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  
  protected $fillable = [
    'name',
    'description',
    'image_link',
    'parent_id'
  ];

  protected $hidden = [
    'created_at', 'updated_at'
  ];

  protected $appends = [
    'sub_categories'
  ];

  public function materials() {
    return $this->hasMany(Material::class);
  }

  public function getSubCategoriesAttribute() {
    $subCategories = $this->where('parent_id', $this->id)->get();

    return $subCategories ?? null;
  }  

}
