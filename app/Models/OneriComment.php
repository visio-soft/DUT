<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneriComment extends Model
{
    use SoftDeletes;

    protected $table = 'oneri_comments';

    protected $fillable = [
        'oneri_id',
        'user_id',
        'parent_id',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Yorumun ait olduğu öneri
     */
    public function oneri(): BelongsTo
    {
        return $this->belongsTo(Oneri::class, 'oneri_id');
    }

    /**
     * Yorumu yazan kullanıcı
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope - Sadece onaylanmış yorumlar
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope - Onay bekleyen yorumlar
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Ana yorum (parent comment)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(OneriComment::class, 'parent_id');
    }

    /**
     * Alt yorumlar (replies)
     */
    public function replies()
    {
        return $this->hasMany(OneriComment::class, 'parent_id')->with(['user', 'replies']);
    }

    /**
     * Onaylanmış alt yorumlar
     */
    public function approvedReplies()
    {
        return $this->hasMany(OneriComment::class, 'parent_id')->where('is_approved', true)->with(['user', 'approvedReplies']);
    }

    /**
     * Scope - Sadece ana yorumlar (parent_id null olanlar)
     */
    public function scopeMainComments($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Yorumun beğenileri
     */
    public function likes()
    {
        return $this->hasMany(OneriCommentLike::class, 'oneri_comment_id');
    }

    /**
     * Beğeni sayısını al
     */
    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }
}
