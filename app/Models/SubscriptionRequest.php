<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SubscriptionRequest extends Model
{
        use HasFactory;

    protected $fillable = [
        'type',
        'period',
        'user_id',
        'planPrice_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function planPrice()
    {
        return $this->belongsTo(PlanPrice::class , 'planPrice_id');
    }
}
