<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistanceRequestPhoto extends Model
{
    protected $table = 'assistance_request_photos';
    protected $fillable = [
        'id',
        'photo',
    ];

    public function assistanceRequest()
    {
        return $this->belongsTo(AssistanceRequest::class);
    }

}
