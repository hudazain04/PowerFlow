<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionRequest extends Model
{
    protected $fillable = [
        'type',
        'period',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
