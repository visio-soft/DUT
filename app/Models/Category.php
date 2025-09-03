<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'start_datetime', 'end_datetime'];

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
