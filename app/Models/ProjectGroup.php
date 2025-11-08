<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_group_suggestion', 'project_group_id', 'suggestion_id')
                    ->withTimestamps();
    }

    public function suggestions(): BelongsToMany
    {
        return $this->belongsToMany(Suggestion::class, 'project_group_suggestion', 'project_group_id', 'suggestion_id')
                    ->withTimestamps();
    }
}
