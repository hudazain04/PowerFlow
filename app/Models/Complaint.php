<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Complaint extends Model
{
    
    use HasFactory;

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
