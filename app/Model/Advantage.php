<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Advantage extends Model
{
    protected $table="privalumai";

    public $timestamps=false;

    protected $fillable=['img','title','description','pozicija','active'];
}
