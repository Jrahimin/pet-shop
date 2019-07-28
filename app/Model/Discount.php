<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    public $timestamps=false;
    protected $fillable = ['title', 'datefrom', 'datetill', 'amount', 'amount_fixed', 'discount_type', 'for_all_user', 'show_discount_percent', 'active'];

    public function packages()
    {
        return $this->belongsToMany('App\Model\Package')->withPivot('product_id');
    }

    public function manufacturers()
    {
        return $this->belongsToMany('App\Model\Manufacturer');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Model\CatalogCategory');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
