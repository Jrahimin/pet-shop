<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = "key";
    public $incrementing = false;
    public $timestamps=false;

    protected $table="nustatymai";

    protected $fillable=['module', 'key', 'value'];
}
