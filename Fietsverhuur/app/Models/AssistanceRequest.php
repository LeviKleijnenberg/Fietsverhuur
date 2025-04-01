<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistanceRequest extends Model
{
    protected $table = 'assistance_requests';
    protected $fillable = [
        'id',
        'problem',
        'bike_number',
        'location_id',
        'bike_number', // Add bike_number to fillable if needed for direct insertion
        'assistance_request_photos_id',
        'map',
    ];

    public function bike(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Bikes::class);
    }
    public function images()
    {
        return $this->hasMany(AssistanceRequestPhoto::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

}
