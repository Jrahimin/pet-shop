<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PackageAttribute extends Model
{
    protected $table="package_attributes";

    public $timestamps=false;

    protected $fillable=['package_id', 'attribute_id', 'value', 'unit_id'];

    public function package()
    {
        return $this->belongsTo('App\Model\Package');
    }
}
