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
        'estimated_duration',
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
    ];

    protected $casts = [
        'estimated_duration' => 'integer',
        'budget' => 'decimal:2',
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

    // Design relationships removed - no longer needed

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
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

    // Design status functionality removed
}
