<?php

namespace App\Models;

use App\ApiHelper\HasFeatureLimit;
use App\ApiHelper\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
     use HasFactory;
     use Translatable,HasFeatureLimit;
    protected $fillable = [
        'name',
        'neighborhood_id',
        'generator_id'
    ];

    public $translatable=[
        'name',
    ];
    public string $featureKey = 'neighborhoods_count';



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
