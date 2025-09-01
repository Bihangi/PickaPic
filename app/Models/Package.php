<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'photographer_id',
        'name',
        'price',
        'details'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the photographer that owns this package
     */
    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }

    /**
     * Get all bookings that use this package
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get formatted price display
     */
    public function getFormattedPriceAttribute()
    {
        return 'LKR ' . number_format($this->price);
    }

    /**
     * Get short description 
     */
    public function getShortDetailsAttribute()
    {
        return strlen($this->details) > 100 
            ? substr($this->details, 0, 100) . '...' 
            : $this->details;
    }
}