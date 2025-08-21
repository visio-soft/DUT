<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'category_id',
        'parent_category_id',
        'title',
        'description',
        'location',
        'budget',
        'image_path',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }
}
