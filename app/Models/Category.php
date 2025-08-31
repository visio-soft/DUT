<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'parent_id', 'icon'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function oneriler(): HasMany
    {
        return $this->hasMany(Oneri::class, 'category_id');
    }

    // Backward compatibility - eski projects() metodunu koruyoruz
    public function projects(): HasMany
    {
        return $this->oneriler();
    }

    // Sadece üst kategorileri getir (parent_id null olanlar)
    public static function getParentCategories()
    {
        return self::whereNull('parent_id')->get();
    }

    // Sadece alt kategorileri getir (parent_id olan/projeler)
    public static function getChildCategories()
    {
        return self::whereNotNull('parent_id')->get();
    }

    // Belirli bir üst kategorinin alt kategorilerini getir
    public function getSubCategories()
    {
        return $this->children;
    }
}
