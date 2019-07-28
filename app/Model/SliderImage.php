<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SliderImage extends Model
{
    protected $fillable =['image','text', 'link', 'parallax', 'active'];
}
