<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Payment extends Model
{
        use HasFactory;
        
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
