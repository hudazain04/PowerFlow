<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ElectricalBox extends Model
{
       use  HasFactory;
       
    protected $fillable = [
        'location',
        'map',
        'number'
    ];

    public function counterbox()
    {
        return $this->hasMany(Counter::class);
    }
    public function area()
    {
        return $this->hasMany(Area::class);
    }

 

}
