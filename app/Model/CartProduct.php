<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    protected  $table = 'cart_products' ;
    protected $fillable = [ 'product_id','package_id','quantity','cart_id'];

    public function cart()
    {
        return $this->belongsTo('App\Model\Cart') ;
    }

    public function product()
    {
        return $this->belongsTo('App\Model\Products') ;
    }

    public function package()
    {
        return $this->belongsTo('App\Model\Package') ;
    }
}
