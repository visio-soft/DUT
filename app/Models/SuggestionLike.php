<?php

namespace App\Models;

use App\Observers\SuggestionLikeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([SuggestionLikeObserver::class])]
class SuggestionLike extends Model
{
    protected $fillable = [
        'user_id',
        'suggestion_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function suggestion(): BelongsTo
    {
        return $this->belongsTo(Suggestion::class);
    }
}
