<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bikes extends Model
{
    protected $table = 'bikes';
    protected $fillable = [
        'id',
        'needs_maintenance',
        'bike_number',
    ];

    public function assistanceRequests()
    {
        return $this->hasMany(AssistanceRequest::class); // Define the inverse relationship
    }
}
