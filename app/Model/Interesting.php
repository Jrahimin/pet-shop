<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Interesting extends Model
{
    protected  $table = "idomu";
    public $timestamps = false;

    protected  $fillable = ['img', 'title', 'autorius', 'data', 'url', 'shortdesc', 'description', 'pozicija', 'active'];

    public function galleries(){
        return $this->hasMany('App\Model\InterestingGallery','skiltis');
    }
}
