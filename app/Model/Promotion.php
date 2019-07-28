<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    public $timestamps=false;
    protected $fillable = ['title', 'code', 'datefrom', 'datetill', 'amount_percent', 'amount_fixed', 'all_product', 'category_check',
        'manufacturer_check', 'show_promotion_percent', 'active'];

    public function products()
    {
        return $this->belongsToMany('App\Model\Products','products_promotion','product_id','promotion_id');
    }
    public function manufacturers()
    {
        return $this->belongsToMany('App\Model\Manufacturer');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Model\CatalogCategory');
    }
}
