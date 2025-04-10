<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'description',
        'status',
        'counter_id',

    ];
    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
}
