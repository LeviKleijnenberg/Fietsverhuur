<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $fillable = [
        'name',
        'id',
        'created_at',
        'updated_at',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
