<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_id',
        'name',
        'email',
        'contact',
        'password',
        'role', 
        'google_profile_data',
        'is_google_registered',
    ];

    protected $casts = [
        'google_profile_data' => 'array', 
    ];
}