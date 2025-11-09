<?php

namespace App\Models;

use App\Observers\ProjectObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[ObservedBy([ProjectObserver::class])]
class Project extends Model implements HasMedia
{
    use InteractsWithMedia,SoftDeletes;

    // Map the legacy 'projects' model to the current 'suggestions' table
    protected $table = 'suggestions';

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Global scope to filter out suggestions (only get projects)
        static::addGlobalScope('projects_only', function (Builder $builder) {
            $builder->whereNull('project_id');
        });
    }

    protected $fillable = [
        'category_id',
        'created_by_id',
        'updated_by_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'min_budget',
        'max_budget',
        'latitude',
        'longitude',
        'address',
        'address_details',
        'city',
        'district',
        'neighborhood',
        'street_avenue',
        'street_road',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'min_budget' => 'decimal:2',
        'max_budget' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function projectGroups(): BelongsToMany
    {
        return $this->belongsToMany(ProjectGroup::class, 'project_group_suggestion', 'suggestion_id', 'project_group_id')
            ->withTimestamps();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id')
            ->withDefault(function ($category, $project) {
                // Get category through first project group
                $firstGroup = $project->projectGroups->first();
                if ($firstGroup) {
                    return $firstGroup->category;
                }
                return $category;
            });
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class, 'project_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    // Design relationship removed - no longer needed

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif',
                'image/webp',
                'image/bmp',
                'image/svg+xml',
            ])
            ->singleFile()
            ->useFallbackUrl('/images/placeholder-project.jpg')
            ->useFallbackPath(public_path('/images/placeholder-project.jpg'));
    }

    // Media conversions disabled to avoid image processing dependencies
    // public function registerMediaConversions(?Media $media = null): void
    // {
    //     // Optimize large images automatically
    //     $this->addMediaConversion('thumb')
    //         ->width(300)
    //         ->height(300)
    //         ->sharpen(10)
    //         ->performOnCollections('images');
    //
    //     $this->addMediaConversion('preview')
    //         ->width(800)
    //         ->height(800)
    //         ->quality(85)
    //         ->performOnCollections('images');
    //
    //     // Optimize original for web
    //     $this->addMediaConversion('web-optimized')
    //         ->width(2000)
    //         ->height(2000)
    //         ->quality(85)
    //         ->performOnCollections('images');
    // }

    // Getter for backward compatibility if needed
    public function getNameAttribute()
    {
        return $this->title;
    }

    /**
     * Check if the project is expired (past end_date)
     */
    public function isExpired(): bool
    {
        if (! $this->end_date) {
            return false;
        }

        return now()->greaterThan($this->end_date);
    }

    /**
     * Get remaining time until project ends
     */
    public function getRemainingTime(): ?array
    {
        if (! $this->end_date || $this->isExpired()) {
            return null;
        }

        $now = now();
        $endTime = $this->end_date->endOfDay(); // Use end of day for end_date
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
     * Get formatted end date for display
     */
    public function getFormattedEndDate(): ?string
    {
        if (! $this->end_date) {
            return null;
        }

        return $this->end_date->format('d.m.Y');
    }
}
