<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'notifications';
    
    protected $fillable = [
        'user_id', 
        'type', 
        'titre', 
        'message', 
        'lien', 
        'lu', 
        'read_at'
    ];
    
    protected $casts = [
        'lu' => 'boolean',
        'read_at' => 'datetime',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}