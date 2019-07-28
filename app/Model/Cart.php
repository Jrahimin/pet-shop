<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart' ;
    protected $fillable = ['ip_address','session_id','last_active'] ;

    public function cartProducts()
    {
        return $this->hasMany('App\Model\CartProduct');
    }
}
