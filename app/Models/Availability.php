<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'is_available',
        'booking_id',
        'user_id',
        'event_details',
        'contact_number',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_available' => 'boolean',
    ];

    /**
     * Get the booking associated with this availability
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user (client) who booked this availability
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get only available slots
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Get only booked slots
     */
    public function scopeBooked($query)
    {
        return $query->where('is_available', false)->whereNotNull('user_id');
    }

    /**
     * Get availabilities for a specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    /**
     * Get availabilities for a date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Check if this availability is booked
     */
    public function isBooked(): bool
    {
        return !$this->is_available && $this->user_id !== null;
    }

    /**
     * Book this availability for a user
     */
    public function bookFor(User $user, array $details = []): bool
    {
        if (!$this->is_available) {
            return false;
        }

        $this->update([
            'is_available' => false,
            'user_id' => $user->id,
            'event_details' => $details['event_details'] ?? null,
            'contact_number' => $details['contact_number'] ?? $user->phone,
        ]);

        return true;
    }

    /**
     * Cancel booking and make availability available again
     */
    public function cancelBooking(): bool
    {
        if ($this->is_available) {
            return false;
        }

        $this->update([
            'is_available' => true,
            'user_id' => null,
            'booking_id' => null,
            'event_details' => null,
            'contact_number' => null,
        ]);

        return true;
    }
}