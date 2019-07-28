<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $table="naujienlaiskio_prenum";

    public $timestamps=false;

    protected $fillable=['email','act','code','reg_date','lang'];
}
