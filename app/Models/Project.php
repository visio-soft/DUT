<?php

namespace App\Models;

use App\Observers\ProjectObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[ObservedBy([ProjectObserver::class])]
class Project extends Model implements HasMedia
{
    use InteractsWithMedia,SoftDeletes;

    // Map the legacy 'projects' model to the current 'oneriler' table
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
        'design_landscape',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'design_completed' => 'boolean',
        'design_landscape' => 'array',
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
    return $this->hasOne(ProjectDesign::class, 'project_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/jpg',
                'image/png'
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
