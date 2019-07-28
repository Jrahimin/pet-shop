<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{


    protected $table = 'darbai';
    public $timestamps = false;

    protected $fillable = ['cat','pavadinimas_lt','tekstas_lt','description','gamintojas','eshop','inproducts','haspacks',
                            'popitem','newitem','price','svoris','akcija' ,'old_price' ,'pozicija' ,'aktyvus' ,'foto','foto2',
                            'prodfile','pavadinimas_en'];




    public function packages()
    {
        return $this->hasMany('App\Model\Package','preke') ;
    }

    public function cartProducts()
    {
        return $this->hasMany('App\Model\CartProduct','product_id');
    }

    public function manufacturer()
    {
        return $this->belongsTo('App\Model\Manufacturer','gamintojas') ;
    }

    public function categories()
    {
        return $this->belongsToMany('App\Model\CatalogCategory','category_darbai','darbai_id','category_id');
    }

    public function promotions()
    {
        return $this->belongsToMany('App\Model\Promotion','products_promotion','promotion_id','product_id');
    }
}
