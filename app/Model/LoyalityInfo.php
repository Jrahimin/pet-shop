<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class LoyalityInfo extends Model
{
    protected $table = "loyality_info";
    public $timestamps=false;

    protected $fillable = ['title', 'description', 'pozicija', 'active'];
}
