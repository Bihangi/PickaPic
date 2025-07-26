<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    protected $table = 'users'; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'contact_number',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
