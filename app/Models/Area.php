<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'area_id',
        'box_id'
    ];

    public function electricalbox()
    {
        return $this->hasMany(ElectricalBox::class);
    }

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class);
    }

}
