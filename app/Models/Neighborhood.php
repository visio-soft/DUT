<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Neighborhood extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'district_id',
        'name',
        'postal_code',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
