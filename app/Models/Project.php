<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'category_id', 'aktif'];

    /**
     * Automatically cascade deletes to related models when soft deleting
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($project) {
            // When soft deleting a project, also soft delete its related oneriler
            if (! $project->isForceDeleting()) {
                $project->oneriler()->delete();
            }
        });

        static::restoring(function ($project) {
            // When restoring a project, also restore its related oneriler
            $project->oneriler()->withTrashed()->restore();
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

    /**
     * Category relationship (kategori)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Oneriler (suggestions) relationship
     */
    public function oneriler(): HasMany
    {
        return $this->hasMany(Oneri::class, 'project_id');
    }

    /**
     * English alias for oneriler() relationship.
     * Returns suggestions belonging to this project.
     */
    public function suggestions(): HasMany
    {
        return $this->oneriler();
    }
}
