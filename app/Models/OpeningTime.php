<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id', 'day', 'start_time', 'end_time', 'is_closed'
    ];

    // Relationship: An opening time belongs to a location
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
