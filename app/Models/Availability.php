<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'photographer_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'booked_by',
        'booking_details',
        'contact_number'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'booking_details' => 'json'
    ];

    // Relationships
    public function photographer()
    {
        return $this->belongsTo(User::class, 'photographer_id');
    }

    public function bookedBy()
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeFuture($query)
    {
        return $query->where('date', '>=', now()->toDateString());
    }

    public function scopeForPhotographer($query, $photographerId)
    {
        return $query->where('photographer_id', $photographerId);
    }

    // Accessors
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->start_time)->format('g:i A') . ' - ' . 
               Carbon::parse($this->end_time)->format('g:i A');
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('F j, Y');
    }

    public function getIsAvailableAttribute()
    {
        return $this->status === 'available' && $this->date >= now()->toDateString();
    }

    public function getIsPastAttribute()
    {
        return $this->date < now()->toDateString();
    }

    
    // Methods
    public function book($userId, $details = null, $contactNumber = null)
    {
        if ($this->status !== 'available') {
            return false;
        }

        if ($this->is_past) {
            return false;
        }

        $this->update([
            'status' => 'booked',
            'booked_by' => $userId,
            'booking_details' => $details,
            'contact_number' => $contactNumber
        ]);

        return true;
    }

    public function cancel()
    {
        if ($this->status === 'booked') {
            $this->update([
                'status' => 'available',
                'booked_by' => null,
                'booking_details' => null,
                'contact_number' => null
            ]);
            return true;
        }

        return false;
    }
}