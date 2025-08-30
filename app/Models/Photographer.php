<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Photographer extends Model
{
    use HasFactory;

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
    ];

    protected $casts = [
        'categories' => 'array',
        'portfolio' => 'array',
        'languages' => 'array',
        'availability' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    
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


}
