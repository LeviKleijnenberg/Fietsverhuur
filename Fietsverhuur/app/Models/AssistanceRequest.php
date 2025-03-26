<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistanceRequest extends Model
{
    protected $table = 'assistance_requests';
    protected $fillable = [
        'id',
        'latlong',
        'description',
        'photos',
        'bike_id',
        'map',
    ];

    public function bike(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Bikes::class, 'bike_id');
    }
    public function photos()
    {
        return $this->hasMany(AssistanceRequestPhoto::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }



}
