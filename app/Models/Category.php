<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = ['name', 'description', 'start_datetime', 'end_datetime', 'district', 'neighborhood', 'country', 'province', 'detailed_address'];

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

    // eski projects() metodunu koruyoruz
    public function projects(): HasMany
    {
        return $this->oneriler();
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
