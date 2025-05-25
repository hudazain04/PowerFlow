<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Plan extends Model
{
    use HasFactory;

    protected $guarded=['id'];

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'plan__features')->withPivot(['value']);
    }

    public function prices()
    {
        return $this->hasMany(PlanPrice::class,'plan_id');
    }
}
