<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
  use SoftDeletes;
  
  protected $fillable = [
    'description',
    'title',
    'image_link',
    'is_active',
    'position',
    'cta_link',
    'cta_title'
  ];

  protected $casts = [
    'is_active' => 'boolean'
  ];

  protected $hidden = [
    'created_at', 'updated_at', 'deleted_at'
  ];  

}
