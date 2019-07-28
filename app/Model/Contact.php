<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = "kontaktai";
    public $timestamps=false;

    protected $fillable = ['title','work_hours','adresas','telefonas','showform','email',
                            'form_email','pozicija','rekvizitai', 'active','img','img2'];
}
