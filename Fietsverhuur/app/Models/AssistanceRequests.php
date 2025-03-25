<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistanceRequests extends Model
{
    protected $table = 'assistance_requests';
    protected $fillable = [
        'id',
        'latlong',
        'description',
        'photos',
        'bike_id',
    ];

    public function bike(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Bikes::class, 'bike_id');
    }


}
