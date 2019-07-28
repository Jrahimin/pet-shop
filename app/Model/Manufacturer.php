<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
   protected $table="gamintojai";

   public $timestamps=false;

   protected $fillable=['url','img','img2','title','description','prod1','prod2','pozicija','prod3','active'];

    public function discounts()
    {
        return $this->belongsToMany('App\Model\Discount');
    }

    public function products()
    {
        return $this->hasMany('App\Model\Products','gamintojas');
    }

    public function promotions()
    {
        return $this->belongsToMany('App\Model\Promotion');
    }
}
