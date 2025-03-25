<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bikes extends Model
{
    protected $table = 'bikes';
    protected $fillable = [
        'id',
        'brand',
        'model',
        'needs_maintenance',
        'latlong',
    ];
}
