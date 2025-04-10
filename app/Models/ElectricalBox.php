<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectricalBox extends Model
{
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
