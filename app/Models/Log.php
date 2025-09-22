<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    /**
     * Indicates if the model should be timestamped.
     * We use custom 'logged_at' instead of created_at/updated_at
     */
    public $timestamps = false;

    protected $fillable = [
        'level',
        'channel',
        'message',
        'context',
        'user_id',
        'user_name',
        'logged_at',
    ];

    protected $casts = [
        'context' => 'array',
        'logged_at' => 'datetime',
    ];

    /**
     * The user who performed the logged action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
