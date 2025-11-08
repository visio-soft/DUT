<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuggestionCommentLike extends Model
{
    protected $table = 'suggestion_comment_likes';

    protected $fillable = [
        'suggestion_comment_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The comment this like belongs to
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(SuggestionComment::class, 'suggestion_comment_id');
    }

    /**
     * The user who created the like
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
