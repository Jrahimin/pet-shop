<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected  $table="baneriai";
    protected  $fillable=['pavadinimas','link','kodas','data_nuo','data_iki','clicktag','parodymai',
                           'paspaudimai','kriterijus','vieta','rodymo_tipas','pages','paspausta','parodyta',
                          'img','lang','aktyvus'];
    public $timestamps=false;
}
