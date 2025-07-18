<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
     use HasFactory;
    protected $fillable = [
        'name',
        'neighborhood_id',
        'generator_id'
    ];

    public function electricalbox()
    {
        return $this->hasMany(ElectricalBox::class);
    }

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function powerGenerator(){
        return $this->belongsTo(PowerGenerator::class);
    }
}
