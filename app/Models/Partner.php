<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{

  protected $fillable = [
    'user_id', 
    'account_group',
    'customer_id',
    'company_name',
    'customer_classification',
    'sales_organization',
    'distribution_channel',
    'division',
    'sales_group',
    'terms_of_payment_key',
    'sales_office',
    'discount_partner',
    'incoterms',
    'credit_control_area',
    'credit_limit',
    'phone',
    'activated_at',
    'expired_at',
    'is_active',
    'category_partner_id'
  ];
  
  protected $hidden = [
    'created_at', 'updated_at', 'deleted_at'
  ];

  protected $appends = [
    'main_address'
  ];

  protected $casts = [
    'is_active' => 'boolean',
    'discount_partner' => 'double'
  ];

  public function user() {
    return $this->belongsTo(User::class);
  }

  public function categoryPartner() {
    return $this->belongsTo(CategoryPartner::class);
  }

  public function getMainAddressAttribute(){
    return $this->addresses->where('is_primary', true)->first() ?? null;
  }

  public function addresses() {
    return $this->hasMany(Address::class);
  }

}
