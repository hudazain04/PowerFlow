<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Payment extends Model
{
        use HasFactory;

    protected $guarded=['id'];

    protected $casts=[
      'date'=>'datetime',
    ];


    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
    public function payments(){
        return $this->belongsTo(Counter::class);
    }
}
