<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['package_id', 'product_id', 'quantity'] ;
}
