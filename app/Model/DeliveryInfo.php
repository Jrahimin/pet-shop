<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class DeliveryInfo extends Model
{
    protected $table = "delivery_info";
    public $timestamps=false;

    protected $fillable = ['title', 'description', 'pozicija', 'active'];
}
