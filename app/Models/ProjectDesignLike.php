<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDesignLike extends Model
{
    protected $fillable = [
        'user_id',
        'project_design_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function projectDesign(): BelongsTo
    {
        return $this->belongsTo(ProjectDesign::class);
    }
}
