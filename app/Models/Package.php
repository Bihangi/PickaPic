<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'photographer_id',
        'name',
        'price',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the photographer that owns the package.
     */
    public function photographer(): BelongsTo
    {
        return $this->belongsTo(Photographer::class);
    }

    /**
     * Get the bookings for the package.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the formatted price attribute.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Scope a query to only include packages for a specific photographer.
     */
    public function scopeForPhotographer($query, $photographerId)
    {
        return $query->where('photographer_id', $photographerId);
    }

    /**
     * Scope a query to order packages by price.
     */
    public function scopeOrderByPrice($query, $direction = 'asc')
    {
        return $query->orderBy('price', $direction);
    }
}