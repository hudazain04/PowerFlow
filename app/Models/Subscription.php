<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Subscription extends Model
{
     use HasFactory;
     
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
