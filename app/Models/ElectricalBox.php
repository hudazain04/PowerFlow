<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ElectricalBox extends Model
{
       use  HasFactory;

    protected $fillable = [
        'location',
        'maps',
        'number',
        'capacity'
    ];

    public function counters()
    {
        return $this->belongsToMany(Counter::class, 'counter__boxes', 'box_id', 'counter_id')
            ->withPivot(['installed_at', 'removed_at'])
            ->wherePivotNull('removed_at');
    }
    public function assignedAreas()
    {
        return $this->belongsToMany(Area::class, 'area__boxes', 'box_id', 'area_id')
            ->withPivot(['assigned_at', 'removed_at'])
            ->wherePivotNull('removed_at');
    }
    public function areas()
    {
        return $this->hasMany(Area::class,'electrical_boxes')
            ->whereNull('electrical_boxes.removed_at');

    }

//    public function counters()
//    {
//        return $this->belongsToMany(Counter::class, 'controller_dex', 'box_id', 'counter_id')
//            ->withPivot(['installed_at', 'removed_at'])
//            ->wherePivotNull('removed_at');
//    }


}
