<?php

namespace App\Models;

use App\ApiHelper\GenerateBoxNumber;
use App\ApiHelper\HasFeatureLimit;
use App\ApiHelper\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ElectricalBox extends Model
{
       use  HasFactory;
       use Translatable,HasFeatureLimit;
    use GenerateBoxNumber;
    protected $fillable = [
        'location',
        'latitude',
        'longitude',
          'number',
        'capacity',
        'generator_id'
    ];
    public string $featureKey = 'boxes_count';

    public function powerGenerator(){
        return $this->BelongsTo(PowerGenerator::class,'generator_id');
    }

    public $translatable=[
        'location',
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
        return $this->belongsToMany(Area::class,'area__boxes','box_id','area_id')
            ->whereNull('area__boxes.removed_at');

    }
//    public function counter(){
//        $this->hasMany(Counter::class);
//    }

//    public function counters()
//    {
//        return $this->belongsToMany(Counter::class, 'controller_dex', 'box_id', 'counter_id')
//            ->withPivot(['installed_at', 'removed_at'])
//            ->wherePivotNull('removed_at');
//    }


}
