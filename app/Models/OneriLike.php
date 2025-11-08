<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OneriLike extends Model
{
    protected $fillable = [
        'user_id',
        'oneri_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function oneri(): BelongsTo
    {
        return $this->belongsTo(Oneri::class);
    }

    /**
     * English alias for oneri() relationship.
     * The suggestion this like belongs to.
     */
    public function suggestion(): BelongsTo
    {
        return $this->oneri();
    }
}
