<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectGroup extends Model
{
    protected $fillable = [
        'name',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'project_group_id');
    }

    // Keep suggestions() as alias for backward compatibility
    public function suggestions(): HasMany
    {
        return $this->projects();
    }
}
