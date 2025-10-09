<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'photographer_id',
        'user_id', 
        'display_name',
        'rating',
        'comment',
        'is_visible'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_visible' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}