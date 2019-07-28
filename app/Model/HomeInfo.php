<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HomeInfo extends Model
{
    protected $table = "titulinio_info";
    public $timestamps=false;

    protected $fillable = ['cat', 'title', 'link', 'description', 'pozicija', 'active'];
}
