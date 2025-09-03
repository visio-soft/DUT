<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

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
}
