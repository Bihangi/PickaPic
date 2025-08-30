<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'photographer_id',
        'title',
        'image', 
    ];

    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }
}
