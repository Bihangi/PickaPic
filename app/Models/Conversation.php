<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Conversation extends Model
{
    protected $fillable = ['user_id', 'photographer_id', 'last_message_at'];


    public function client(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function photographer(): BelongsTo { return $this->belongsTo(User::class, 'photographer_id'); }

    public function messages(): HasMany
    {
    return $this->hasMany(Message::class)->orderBy('created_at');
    }

    protected $casts = [
        'last_message_at' => 'datetime',
    ];
}