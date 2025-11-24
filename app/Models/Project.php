<?php

namespace App\Models;

use App\Observers\ProjectObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
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
        'status',
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

    protected $attributes = [
        'status' => 'draft',
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
     * Gracefully handle legacy statuses like "pending" that belong to suggestions.
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return \App\Enums\ProjectStatusEnum::tryFrom($value ?? '')
                    ?? \App\Enums\ProjectStatusEnum::DRAFT;
            },
            set: function ($value) {
                if ($value instanceof \App\Enums\ProjectStatusEnum) {
                    return $value->value;
                }

                return \App\Enums\ProjectStatusEnum::tryFrom((string) $value)?->value
                    ?? \App\Enums\ProjectStatusEnum::DRAFT->value;
            }
        );
    }

    public function isExpired(): bool
    {
        return $this->end_date instanceof Carbon
            ? $this->end_date->isPast()
            : false;
    }

    public function isUpcoming(): bool
    {
        return $this->start_date instanceof Carbon
            ? $this->start_date->isFuture()
            : false;
    }

    public function getRemainingTime(): ?array
    {
        if (! ($this->end_date instanceof Carbon) || $this->isExpired()) {
            return null;
        }

        $diff = Carbon::now()->diff($this->end_date);

        return [
            'days' => $diff->days,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
            'formatted' => $this->formatRemainingTime($diff),
        ];
    }

    protected function formatRemainingTime(\DateInterval $diff): string
    {
        if ($diff->days > 0) {
            return "{$diff->days} gün {$diff->h} saat";
        }

        if ($diff->h > 0) {
            return "{$diff->h} saat {$diff->i} dakika";
        }

        if ($diff->i > 0) {
            return "{$diff->i} dakika";
        }

        return "{$diff->s} saniye";
    }

    public function getFormattedEndDateAttribute(): ?string
    {
        return $this->end_date instanceof Carbon
            ? $this->end_date->format('d.m.Y H:i')
            : null;
    }
}
