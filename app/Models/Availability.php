<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'photographer_id', 
        'date',
        'start_time',
        'end_time',
        'status',  
        'client_id',  
        'booking_id',
        'event_details',
        'contact_number',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the photographer who owns this availability
     */
    public function photographer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'photographer_id');
    }

    /**
     * Get the client who booked this availability
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the booking associated with this availability
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get only available slots
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Get only booked slots
     */
    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
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
        return $this->status === 'booked';
    }

    /**
     * Check if this availability is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Book this availability for a client
     */
    public function bookFor(User $client, array $details = []): bool
    {
        if ($this->status !== 'available') {
            return false;
        }

        $this->update([
            'status' => 'booked',
            'client_id' => $client->id,
            'event_details' => $details['event_details'] ?? null,
            'contact_number' => $details['contact_number'] ?? $client->phone,
        ]);

        return true;
    }

    /**
     * Cancel booking and make availability available again
     */
    public function cancelBooking(): bool
    {
        if ($this->status !== 'booked') {
            return false;
        }

        $this->update([
            'status' => 'available',
            'client_id' => null,
            'booking_id' => null,
            'event_details' => null,
            'contact_number' => null,
        ]);

        return true;
    }
}