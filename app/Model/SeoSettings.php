<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SeoSettings extends Model
{
    protected $table="seo_nustatymai";
    public $timestamps=false;

    protected $fillable=['id', 'lenta', 'lang', 'meta_key', 'meta_desc'];
}
