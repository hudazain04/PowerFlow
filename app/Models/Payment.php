<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'amount',
        'date',
        'current_spending',
        'counter_id'
    ];
    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
}
