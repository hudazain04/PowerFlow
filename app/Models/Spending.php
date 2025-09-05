<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Spending extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'consume',
        'counter_id'
    ];
    protected $casts=[
        'date'=>'datetime',
    ];

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
}

