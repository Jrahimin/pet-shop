<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class CustomerInfo extends Model
{
    protected $table = "customer_info";
    public $timestamps=false;

    protected $fillable = ['title', 'description', 'pozicija', 'active'];
}
