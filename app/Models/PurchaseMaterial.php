<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseMaterial extends Model
{
  
  protected $primaryKey = 'material_id';

  protected $fillable = [
    'material_id',
    'amount'
  ];

  public function material(){
    return $this->belongsTo(Material::class);
  }

}
