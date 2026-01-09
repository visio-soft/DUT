<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuggestionComment extends Model
{
    use SoftDeletes;

    protected $table = 'suggestion_comments';

    protected $fillable = [
        'suggestion_id',
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
     * The suggestion this comment belongs to
     */
    public function suggestion(): BelongsTo
    {
        return $this->belongsTo(Suggestion::class, 'suggestion_id');
    }

    /**
     * The user who created the comment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope - Only approved comments
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope - Pending comments
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Parent comment
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(SuggestionComment::class, 'parent_id');
    }

    /**
     * Replies
     */
    public function replies()
    {
        return $this->hasMany(SuggestionComment::class, 'parent_id')->with(['user', 'replies']);
    }

    /**
     * Approved replies
     */
    public function approvedReplies()
    {
        return $this->hasMany(SuggestionComment::class, 'parent_id')->where('is_approved', true)->with(['user', 'approvedReplies']);
    }

    /**
     * Scope - Only main comments (parent_id is null)
     */
    public function scopeMainComments($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Comment likes
     */
    public function likes()
    {
        return $this->hasMany(SuggestionCommentLike::class, 'suggestion_comment_id');
    }

    /**
     * Get likes count
     */
    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    /**
     * Get translated attribute
     */
    public function getTranslatedAttribute(string $field, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        // If Turkish or locale is Turkish, return original
        if ($locale === 'tr') {
            return $this->$field ?? '';
        }

        return app(\App\Services\TranslationService::class)->translateModel($this, $field, $locale);
    }

    /**
     * Get translated comment
     */
    public function getTranslatedCommentAttribute(): string
    {
        return $this->getTranslatedAttribute('comment');
    }
}
