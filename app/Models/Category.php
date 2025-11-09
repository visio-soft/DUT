<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = ['name'];

    /**
     * Automatically cascade deletes to related models when soft deleting
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            // When soft deleting a category, cascade through the hierarchy
            if (! $category->isForceDeleting()) {
                // Delete project groups (which will cascade to projects via DB constraint)
                $category->projectGroups()->delete();
            }
        });

        static::restoring(function ($category) {
            // When restoring a category, also restore its project groups
            $category->projectGroups()->withTrashed()->restore();
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
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [];

    public function projectGroups(): HasMany
    {
        return $this->hasMany(ProjectGroup::class, 'category_id');
    }

    /**
     * Get all projects through project groups.
     * Since projects can belong to multiple groups, this returns distinct projects.
     */
    public function getProjectsAttribute()
    {
        return Project::whereHas('projectGroups', function ($query) {
            $query->where('project_groups.category_id', $this->id);
        })->whereNull('project_id')->get();
    }
    
    /**
     * Get projects count for this category
     */
    public function getProjectsCountAttribute()
    {
        return Project::whereHas('projectGroups', function ($query) {
            $query->where('project_groups.category_id', $this->id);
        })->whereNull('project_id')->count();
    }

    /**
     * Get all suggestions through projects.
     * Note: This is an indirect relationship through the hierarchy.
     */
    public function suggestions(): HasMany
    {
        // This returns suggestions that are associated with projects in this category
        // For direct suggestions on a category (if any remain), use the direct relationship
        return $this->hasMany(Suggestion::class, 'category_id')
            ->whereNotNull('project_id'); // Only suggestions, not projects
    }

    /**
     * Check if the project is expired (past end_datetime)
     */
    public function isExpired(): bool
    {
        if (! $this->end_datetime) {
            return false;
        }

        return now()->greaterThan($this->end_datetime);
    }

    /**
     * Get remaining time until project ends
     */
    public function getRemainingTime(): ?array
    {
        if (! $this->end_datetime || $this->isExpired()) {
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
            'formatted' => $this->formatRemainingTime($diff),
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
        if (! $this->end_datetime) {
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
