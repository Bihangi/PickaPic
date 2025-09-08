<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Photographer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        // Existing fields
        'google_id',
        'name',
        'email',
        'contact',
        'password',
        'role',
        'google_profile_data',
        'is_google_registered',
        'bio',
        'user_id',

        // New fields
        'categories',
        'location',
        'languages',
        'experience',
        'profile_image',
        'availability',
        'website',
        'instagram',
        'is_verified'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'categories' => 'array',
        'portfolio' => 'array',
        'languages' => 'array',
        'availability' => 'array',
        'is_verified' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Accessors / Mutators
     */
    // Get categories as array
    public function getCategoriesAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }

    // Save categories as comma-separated string
    public function setCategoriesAttribute($value)
    {
        $this->attributes['categories'] = is_array($value) ? implode(',', $value) : $value;
    }

    /**
     * Custom Attributes
     */
    public function getUpcomingAvailabilityAttribute()
    {
        return $this->availabilities()
                    ->where('status', 'available')
                    ->where('date', '>=', now()->toDateString())
                    ->where('date', '<=', now()->addDays(30)->toDateString())
                    ->orderBy('date')
                    ->orderBy('start_time')
                    ->get();
    }

    public function getActivePackagesCountAttribute()
    {
        return $this->packages()->count();
    }

    public function getTotalBookingsCountAttribute()
    {
        return $this->bookings()->count();
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Chat System Relationships
     */
    public function conversations()
    {
        return $this->hasMany(\App\Models\Conversation::class, 'photographer_id', 'id');
    }

    public function clientConversations()
    {
        return $this->hasMany(\App\Models\Conversation::class, 'user_id', 'id');
    }

    public function sentMessages()
    {
        return $this->hasMany(\App\Models\Message::class, 'sender_id', 'id');
    }
}
