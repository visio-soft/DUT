<?php

namespace App\Models;

use App\Observers\OneriObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[ObservedBy([OneriObserver::class])]
class Oneri extends Model implements HasMedia
{
    use InteractsWithMedia,SoftDeletes;

    protected $table = 'oneriler';

    protected $fillable = [
        'category_id',
        'created_by_id',
        'updated_by_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'budget',
        'latitude',
        'longitude',
        'address',
        'address_details',
        'city',
        'district',
        'neighborhood',
        'street_cadde',
        'street_sokak',
        'design_completed',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'design_completed' => 'boolean',
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

    public function design(): HasOne
    {
    // Explicit foreign key: project_designs.project_id (table uses project_id constrained to oneriler)
    return $this->hasOne(ProjectDesign::class, 'project_id');
    }

    public function likes()
    {
        return $this->hasManyThrough(
            ProjectDesignLike::class,
            ProjectDesign::class,
            'project_id', // Foreign key on project_designs table
            'project_design_id', // Foreign key on project_design_likes table
            'id', // Local key on oneriler table
            'id' // Local key on project_designs table
        );
    }

    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

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
                'image/svg+xml'
            ])
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Media conversions disabled to avoid errors
        // If thumbnails are needed, use CSS resizing in frontend
    }

    // Getter for backward compatibility if needed
    public function getNameAttribute()
    {
        return $this->title;
    }
}
