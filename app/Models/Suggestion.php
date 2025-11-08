<?php

namespace App\Models;

use App\Observers\SuggestionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[ObservedBy([SuggestionObserver::class])]
class Suggestion extends Model implements HasMedia
{
    use InteractsWithMedia,SoftDeletes;

    protected $table = 'suggestions';

    protected $fillable = [
        'category_id',
        'created_by_id',
        'updated_by_id',
        'title',
        'description',
        'estimated_duration',
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
        'estimated_duration' => 'integer',
        'min_budget' => 'decimal:2',
        'max_budget' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Remove design-related appends since design functionality is removed
    protected $appends = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function projectGroups(): BelongsToMany
    {
        return $this->belongsToMany(ProjectGroup::class, 'project_group_suggestion', 'suggestion_id', 'project_group_id')
            ->withTimestamps();
    }

    public function likes(): HasMany
    {
        return $this->hasMany(SuggestionLike::class, 'suggestion_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(SuggestionComment::class, 'suggestion_id');
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(SuggestionComment::class)->where('is_approved', true)->whereNull('parent_id');
    }

    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->count();
    }

    public function getApprovedCommentsCountAttribute(): int
    {
        return $this->approvedComments()->count();
    }

    // Design relationships removed - no longer needed

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->singleFile()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/webp',
                'image/gif',
                'image/bmp',
            ])
            ->useFallbackUrl('/images/placeholder-suggestion.jpg')
            ->useFallbackPath(public_path('/images/placeholder-suggestion.jpg'));
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

    // Design status functionality removed
}
