<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationView extends Model
{
    protected $table = 'location_view';

    protected $primaryKey = 'uid';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $guarded = [];
}
