<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'aktif'];

    /**
     * Cast attributes to native types.
     */
    protected $casts = [
        'aktif' => 'boolean',
    ];

    /**
     * Categories relationship (alt kategoriler)
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'main_category_id');
    }

    /**
     * Get active categories
     */
    public function activeCategories(): HasMany
    {
        return $this->categories()->where('aktif', true);
    }
}
