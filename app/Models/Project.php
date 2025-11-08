<?php

namespace App\Models;

use App\Observers\ProjectObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    protected $fillable = [
        'category_id',
        'created_by_id',
        'updated_by_id',
        'project_group_id',
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

    public function projectGroup(): BelongsTo
    {
        return $this->belongsTo(ProjectGroup::class, 'project_group_id');
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
}
