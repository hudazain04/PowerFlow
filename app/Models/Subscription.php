<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'provider_id',
        'start_time',
        'price',
        'period'
    ];
    public function serviceprovider()
    {
        return $this->belongsTo(PowerGenerator::class);
    }
}
