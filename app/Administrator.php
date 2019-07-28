<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;


class Administrator extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $fillable=['user','name','surname','email','telephone','active','status','password'];
    protected $hidden = [
        'password','remember_token'
    ];
   protected $guard_name='admin';
}
