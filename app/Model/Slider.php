<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = "slider";
    public $timestamps=false;

    protected $fillable = ['img', 'title', 'link', 'description', 'pozicija', 'active'];
}
