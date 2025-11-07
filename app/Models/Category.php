<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = ['name', 'description', 'parent_id', 'start_datetime', 'end_datetime', 'hide_budget', 'district', 'neighborhood', 'country', 'province', 'detailed_address'];

    /**
     * Automatically cascade deletes to related models when soft deleting
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            // When soft deleting a category, also soft delete its related oneriler
            if (!$category->isForceDeleting()) {
                $category->oneriler()->delete();
            }
        });

        static::restoring(function ($category) {
            // When restoring a category, also restore its related oneriler
            $category->oneriler()->withTrashed()->restore();
        });
    }

    /**
     * Cast attributes to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'hide_budget' => 'boolean',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [];

    public function oneriler(): HasMany
    {
        return $this->hasMany(Oneri::class, 'category_id');
    }

    /**
     * Alias for oneriler() for backward compatibility.
     * Returns the same relationship as oneriler().
     * 
     * @deprecated Use oneriler() instead
     * @return HasMany
     */
    public function projects(): HasMany
    {
        return $this->oneriler();
    }

    /**
     * Parent category relationship
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Children categories relationship
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Check if the project is expired (past end_datetime)
     */
    public function isExpired(): bool
    {
        if (!$this->end_datetime) {
            return false;
        }

        return now()->greaterThan($this->end_datetime);
    }

    /**
     * Get remaining time until project ends
     */
    public function getRemainingTime(): ?array
    {
        if (!$this->end_datetime || $this->isExpired()) {
            return null;
        }

        $now = now();
        $endTime = $this->end_datetime;
        $diff = $now->diff($endTime);

        return [
            'days' => $diff->days,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
            'total_hours' => ($diff->days * 24) + $diff->h,
            'total_minutes' => (($diff->days * 24) + $diff->h) * 60 + $diff->i,
            'formatted' => $this->formatRemainingTime($diff)
        ];
    }

    /**
     * Format remaining time for display
     */
    private function formatRemainingTime($diff): string
    {
        if ($diff->days > 0) {
            return "{$diff->days} gün {$diff->h} saat";
        } elseif ($diff->h > 0) {
            return "{$diff->h} saat {$diff->i} dakika";
        } elseif ($diff->i > 0) {
            return "{$diff->i} dakika";
        } else {
            return "{$diff->s} saniye";
        }
    }

    /**
     * Get formatted end datetime for display
     */
    public function getFormattedEndDatetime(): ?string
    {
        if (!$this->end_datetime) {
            return null;
        }

        return $this->end_datetime->format('d.m.Y H:i');
    }

    /**
     * Register media collections used by Category.
     */
    public function registerMediaCollections(): void
    {
        // collection for project related files (images, docs, etc.)
        $this->addMediaCollection('project_files');
    }
}
