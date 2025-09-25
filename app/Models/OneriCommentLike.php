<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OneriCommentLike extends Model
{
    protected $table = 'oneri_comment_likes';

    protected $fillable = [
        'oneri_comment_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Beğeninin ait olduğu yorum
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(OneriComment::class, 'oneri_comment_id');
    }

    /**
     * Beğeniyi yapan kullanıcı
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
