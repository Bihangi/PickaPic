<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PremiumRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'photographer_id',
        'package_type',
        'amount_paid',
        'payment_slip',
        'message',
        'status',
        'requested_at',
        'processed_at',
        'expires_at'
    ];

    protected $dates = [
        'requested_at',
        'processed_at',
        'expires_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    // Package definitions - Fixed to match your blade file prices
    const PACKAGES = [
        'monthly' => [
            'name' => 'Monthly Premium',
            'price' => 2500,
            'duration_months' => 1,
            'features' => [
                'Top listing with premium badge',
                'Priority in search results',
                'Enhanced profile visibility',
                'Premium support'
            ]
        ],
        'quarterly' => [
            'name' => 'Quarterly Premium',
            'price' => 6000, // Fixed from 7000 to match blade file
            'duration_months' => 3,
            'features' => [
                'Top listing with premium badge',
                'Priority in search results',
                'Enhanced profile visibility',
                'Premium support',
                'Monthly performance report'
            ]
        ],
        'yearly' => [
            'name' => 'Yearly Premium',
            'price' => 20000, // Fixed from 25000 to match blade file
            'duration_months' => 12,
            'features' => [
                'Top listing with premium badge',
                'Priority in search results',
                'Enhanced profile visibility',
                'Premium support',
                'Monthly performance reports',
                'Featured in newsletters'
            ]
        ]
    ];

    /**
     * Relationship with photographer
     */
    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for active premium (approved and not expired)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Check if request is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if request is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if premium is active (approved and not expired)
     */
    public function isActive()
    {
        return $this->isApproved() && 
               (is_null($this->expires_at) || $this->expires_at > now());
    }

    /**
     * Check if premium is expired
     */
    public function isExpired()
    {
        return $this->isApproved() && 
               !is_null($this->expires_at) && 
               $this->expires_at <= now();
    }

    /**
     * Get package details
     */
    public function getPackageDetails()
    {
        return self::PACKAGES[$this->package_type] ?? null;
    }

    /**
     * Get package name
     */
    public function getPackageName()
    {
        $package = $this->getPackageDetails();
        return $package['name'] ?? ucfirst($this->package_type);
    }

    /**
     * Get package duration in months
     */
    public function getPackageDuration()
    {
        $package = $this->getPackageDetails();
        return $package['duration_months'] ?? 1;
    }

    /**
     * Calculate expiry date based on package type
     */
    public function calculateExpiryDate()
    {
        if (!$this->processed_at) {
            return null;
        }

        $duration = $this->getPackageDuration();
        return Carbon::parse($this->processed_at)->addMonths($duration);
    }

    /**
     * Approve the premium request
     */
    public function approve()
    {
        $this->status = 'approved';
        $this->processed_at = now();
        $this->expires_at = $this->calculateExpiryDate();
        $this->save();

        return $this;
    }

    /**
     * Reject the premium request
     */
    public function reject()
    {
        $this->status = 'rejected';
        $this->processed_at = now();
        $this->save();

        return $this;
    }

    /**
     * Get days remaining until expiry
     */
    public function getDaysRemaining()
    {
        if (!$this->expires_at) {
            return null;
        }

        return now()->diffInDays($this->expires_at, false);
    }

    /**
     * Check if premium expires soon (within 7 days)
     */
    public function expiresSoon()
    {
        $daysRemaining = $this->getDaysRemaining();
        return $daysRemaining !== null && $daysRemaining <= 7 && $daysRemaining >= 0;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rs. ' . number_format($this->amount_paid, 2);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'orange',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray'
        };
    }

    /**
     * Boot method to set default values
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->requested_at) {
                $model->requested_at = now();
            }
        });
    }
}