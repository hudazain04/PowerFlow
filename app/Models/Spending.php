<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spending extends Model
{
    protected $fillable = [
        'date',
        'consume',
        'counter_id'
    ];
    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
}
