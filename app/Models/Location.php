<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Location extends Model
{
    public const TYPE_COUNTRY = 'country';
    public const TYPE_CITY = 'city';
    public const TYPE_DISTRICT = 'district';
    public const TYPE_NEIGHBORHOOD = 'neighborhood';

    public const TYPES = [
        self::TYPE_COUNTRY,
        self::TYPE_CITY,
        self::TYPE_DISTRICT,
        self::TYPE_NEIGHBORHOOD,
    ];

    protected $fillable = [
        'name',
        'slug',
        'type',
        'parent_id',
    ];

    protected $appends = [
        'full_path',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $location): void {
            if (blank($location->slug)) {
                $location->slug = $location->generateUniqueSlug($location->name);
            }
        });

        static::updating(function (self $location): void {
            if ($location->isDirty('name') || blank($location->slug)) {
                $location->slug = $location->generateUniqueSlug($location->name, $location->getKey());
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeCountries(Builder $query): Builder
    {
        return $query->ofType(self::TYPE_COUNTRY);
    }

    public function scopeCities(Builder $query): Builder
    {
        return $query->ofType(self::TYPE_CITY);
    }

    public function scopeDistricts(Builder $query): Builder
    {
        return $query->ofType(self::TYPE_DISTRICT);
    }

    public function scopeNeighborhoods(Builder $query): Builder
    {
        return $query->ofType(self::TYPE_NEIGHBORHOOD);
    }

    public static function typeLabels(): array
    {
        return [
            self::TYPE_COUNTRY => __('common.country'),
            self::TYPE_CITY => __('common.city'),
            self::TYPE_DISTRICT => __('common.district'),
            self::TYPE_NEIGHBORHOOD => __('common.neighborhood'),
        ];
    }

    public function getFullPathAttribute(): string
    {
        $segments = [];
        $current = $this;

        while ($current !== null) {
            $segments[] = $current->name;
            $current = $current->parent;
        }

        return implode(' / ', array_reverse($segments));
    }

    protected function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            self::query()
                ->when($ignoreId, fn (Builder $query): Builder => $query->whereKeyNot($ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
