<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratorSetting extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    protected $casts=[
        'nextDueDate'=>'datetime',
    ];
}
