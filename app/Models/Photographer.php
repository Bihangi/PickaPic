<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photographer extends Model
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
    'bio',
    'profile_picture',
    'user_id',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
