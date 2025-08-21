<?php

namespace App\Models;

use App\ApiHelper\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Plan extends Model
{
    use HasFactory;
    use Translatable;

    protected $guarded=['id'];
    public $translatable=[
        'name',
        'target',
        'description',
    ];
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'plan__features')->withPivot(['value']);
    }

    public function prices()
    {
        return $this->hasMany(PlanPrice::class,'plan_id');
    }
}
