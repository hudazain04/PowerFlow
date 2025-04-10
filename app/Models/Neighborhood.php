<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model
{
    protected $fillable = [
        'name',
        'location'
    ];
    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
