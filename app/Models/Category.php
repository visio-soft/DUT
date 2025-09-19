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

    protected $fillable = ['name', 'start_datetime', 'end_datetime'];

    /**
     * Cast attributes to native types.
     *
     * @var array
     */
    protected $casts = [];

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
