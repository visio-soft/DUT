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

/**
 * @property int $id
 * @property int $category_id
 * @property int $created_by_id
 * @property string $title
 * @property string $description
 * @property string $start_date
 * @property string $end_date
 * @property float $budget
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $address
 * @property string|null $city
 * @property string|null $district
 * @property string|null $neighborhood
 * @property string|null $street_cadde
 * @property string|null $street_sokak
 * @property string|null $address_details
 * @property bool $design_completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Category $category
 * @property-read User $createdBy
 * @property-read ProjectDesign|null $design
 */
#[ObservedBy([ProjectObserver::class])]
class Project extends Model implements HasMedia
{
    use InteractsWithMedia,SoftDeletes;

    protected $fillable = [
        'category_id',
        'created_by_id',
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

    public function design(): HasOne
    {
        return $this->hasOne(ProjectDesign::class);
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
        // Conversion'ları devre dışı bırak - hata kaynağı
        // Eğer thumbnail gerekirse frontend'de CSS ile küçültelim

        /*
        // Sadece raster (bitmap) resimler için conversion yapılır
        $this->addMediaConversion('preview')
            ->width(800)
            ->height(600)
            ->nonQueued()
            ->performOnCollections('images')
            ->optimize()
            ->skipOnFailure() // Hata durumunda conversion'u atla
            ->performOnlyOnMimeTypes([
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif',
                'image/webp',
                'image/bmp'
            ]);

        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(200)
            ->nonQueued()
            ->performOnCollections('images')
            ->optimize()
            ->skipOnFailure() // Hata durumunda conversion'u atla
            ->performOnlyOnMimeTypes([
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif',
                'image/webp',
                'image/bmp'
            ]);
        */
    }

    // Getter for backward compatibility if needed
    public function getNameAttribute()
    {
        return $this->title;
    }
}
