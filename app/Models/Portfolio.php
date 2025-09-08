<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'photographer_id',
        'file_path',
        'original_name',
        'file_size',
        'mime_type',
        'title',
        'description',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'file_size' => 'integer'
    ];

    /**
     * Get the photographer that owns the portfolio item
     */
    public function photographer(): BelongsTo
    {
    
        return $this->belongsTo(Photographer::class, 'photographer_id');
    }

    /**
     * Get the file URL
     */
    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get human readable file size
     */
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}