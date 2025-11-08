<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
    protected $fillable = [
        'name',
    ];

    public function projectGroups(): HasMany
    {
        return $this->hasMany(ProjectGroup::class);
    }
}
