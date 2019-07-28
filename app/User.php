<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    public $timestamps=false;

    protected $fillable = [
        'name', 'email', 'password','surname','phone','city','zip_code','address','iscompany','company_title','company_code',
        'company_vatcode','has_discount','discount_from','discount_to','discount_type','discount_percent','discount_cat',
        'discount_items','regdate','last_login','active','remember_token'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function discounts()
    {
        return $this->belongsToMany('App\Model\Discount');
    }
}
