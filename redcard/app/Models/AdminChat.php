<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminChat extends Model
{
    protected $fillable = [
        'user_id',
        'loan_id',
        'return_location_id',
        'channel',
        'message',
        'context',
        'condition',
        'review',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}
