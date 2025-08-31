<?php

namespace App\Models;

use App\Observers\ProjectDesignObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([ProjectDesignObserver::class])]
class ProjectDesign extends Model
{
    protected $fillable = [
        'project_id',
        'design_data',
    ];

    protected $casts = [
        'design_data' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Oneri::class, 'project_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ProjectDesignLike::class);
    }

    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    public function isLikedByUser($userId): bool
    {
        if (!$userId) {
            return false;
        }

        return $this->likes()->where('user_id', $userId)->exists();
    }
}
