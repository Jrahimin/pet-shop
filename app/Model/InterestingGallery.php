<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InterestingGallery extends Model
{
    protected $table="idomu_nuotraukos";

    public $timestamps=false;

    protected $fillable=['skiltis', 'video', 'aprasymas_lt', 'aprasymas_en', 'aprasymas_ru', 'img', 'pozicija'];

    public function Interesting(){
        return $this->belongsTo('App\Model\Interesting','skiltis');
    }
}
