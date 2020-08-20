<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialImage extends Model
{
  
  protected $fillable = [
    'material_id', 'image_link', 'position'
  ];

  public function material() {
    return $this->belongsTo(Material::class);
  }

}
