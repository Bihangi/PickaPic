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
        'comment'
    ];

    protected $casts = [
        'rating' => 'integer',
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