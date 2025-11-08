<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'main_category_id', 'aktif'];

    /**
     * Automatically cascade deletes to related models when soft deleting
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            // When soft deleting a category, also soft delete its related oneriler
            if (! $category->isForceDeleting()) {
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
        'aktif' => 'boolean',
    ];

    public function oneriler(): HasMany
    {
        return $this->hasMany(Oneri::class, 'category_id');
    }

    /**
     * English alias for oneriler() relationship.
     * Returns suggestions belonging to this category.
     */
    public function suggestions(): HasMany
    {
        return $this->oneriler();
    }

    /**
     * Alias for oneriler() for backward compatibility.
     * Returns the same relationship as oneriler().
     *
     * @deprecated Use suggestions() instead
     */
    public function projects(): HasMany
    {
        return $this->oneriler();
    }

    /**
     * Main category relationship (ana kategori)
     */
    public function mainCategory(): BelongsTo
    {
        return $this->belongsTo(MainCategory::class, 'main_category_id');
    }
}
