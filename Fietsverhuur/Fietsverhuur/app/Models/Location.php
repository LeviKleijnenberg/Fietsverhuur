<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table = 'locations';
    protected $fillable = [
        'location_name',
        'latitude',
        'longitude',
        'company_id',
        'location_address',
        'location_phone',
        'location_email',
        'created_at',
        'updated_at',
        'address',
        'zoom',
        'map',
        'location_code',
        'street_number',
        'city',
        'state',
        'state_short',
        'post_code',
        'country',
        'country_short',
        'visible',
        'street_name',
        'reservation_url'
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function openingTimes()
    {
        return $this->hasMany(OpeningTime::class);
    }

    public function assistanceRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\AssistanceRequest::class, 'location_id');
    }

}
