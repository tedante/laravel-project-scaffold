<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
  protected $fillable = [
    'order_id',
    'status',
    'notes',
    'updated_by'
  ];

  public function order() {
    return $this->belongsTo(Order::class);
  }
}
