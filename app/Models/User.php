<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash; 
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'contact',
        'location',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', 
    ];

    public function isPhotographer()
    {
        return $this->role === 'photographer';
    }

    public function photographer()
    {
        return $this->hasOne(Photographer::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all bookings made by this user
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function clientConversations()
    {
        return $this->hasMany(Conversation::class, 'user_id');
    }

    public function photographerConversations()
    {
        return $this->hasMany(Conversation::class, 'photographer_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
}