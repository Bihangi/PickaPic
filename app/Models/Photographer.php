<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\PremiumRequest;

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
     * Premium Requests Relationships
     */
    public function premiumRequests()
    {
        return $this->hasMany(PremiumRequest::class);
    }

    public function latestPremiumRequest()
    {
        return $this->hasOne(PremiumRequest::class)->latest();
    }

    public function activePremiumRequest()
    {
        // Prefer the scope `active()` if defined in PremiumRequest model
        return $this->hasOne(PremiumRequest::class)->active();
    }

    /**
     * Accessors / Mutators
     */
    public function getCategoriesAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }

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

    /**
     * Premium Helpers
     */
    public function isPremium()
    {
        return $this->activePremiumRequest()->exists();
    }

    public function hasPendingPremiumRequest()
    {
        return $this->premiumRequests()->pending()->exists();
    }

    public function getPremiumExpiryDate()
    {
        $activePremium = $this->activePremiumRequest()->first();
        return $activePremium ? $activePremium->expires_at : null;
    }

    public function premiumExpiresSoon()
    {
        $activePremium = $this->activePremiumRequest()->first();
        return $activePremium ? $activePremium->expiresSoon() : false;
    }

    public function getPremiumDaysRemaining()
    {
        $activePremium = $this->activePremiumRequest()->first();
        return $activePremium ? $activePremium->getDaysRemaining() : null;
    }

    public function getPremiumBadge()
    {
        if ($this->isPremium()) {
            return [
                'show' => true,
                'class' => 'bg-gradient-to-r from-yellow-400 to-orange-500 text-white',
                'icon' => 'fas fa-crown',
                'text' => 'TOP'
            ];
        }
        return ['show' => false];
    }

    /**
     * Query Scopes
     */
    public function scopePremium($query)
    {
        return $query->whereHas('activePremiumRequest');
    }

    public function scopeOrderByPremium($query)
    {
        return $query->leftJoin('premium_requests', function($join) {
            $join->on('photographers.id', '=', 'premium_requests.photographer_id')
                ->where('premium_requests.status', '=', 'approved')
                ->where('premium_requests.expires_at', '>', now());
        })
        ->orderByRaw('CASE WHEN premium_requests.id IS NOT NULL THEN 0 ELSE 1 END')
        ->orderBy('photographers.created_at', 'desc')
        ->select('photographers.*');
    }
}
