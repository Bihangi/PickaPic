<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['photographer_id','name','price','details'];

    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }
}
