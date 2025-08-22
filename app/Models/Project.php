<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Project extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'category_id',
        'title',
        'name',
        'description',
        'start_date',
        'end_date',
        'budget',
        'latitude',
        'longitude',
        'address',
        'address_details',
        'city',
        'district',
        'neighborhood',
        'street_cadde',
        'street_sokak',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }
    
    protected static function booted()
    {
        static::creating(function ($project) {
            if (empty($project->name) && !empty($project->title)) {
                $project->name = $project->title;
            }
        });
    }
}
