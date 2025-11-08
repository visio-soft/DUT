<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectGroup extends Model
{
    protected $fillable = [
        'name',
        'project_category_id',
    ];

    public function projectCategory(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'project_group_id');
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class, 'project_group_id');
    }
}
