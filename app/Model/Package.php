<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table="pakuotes";

    public $timestamps=false;

    protected $fillable=['preke','pavadinimas','kaina','svoris','akcija','sena_kaina'];

    public function discounts()
    {
        return $this->belongsToMany('App\Model\Discount')->withPivot('product_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Model\Products');
    }

    public function cartProducts()
    {
        return $this->hasMany('App\Model\CartProduct','package_id') ;
    }

    public function attributes()
    {
        return $this->hasMany('App\Model\PackageAttribute','package_id');
    }
}
