<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feature extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function scopeFilter($query,array $filters)
    {

        $query->when($filters['plan_id'] ?? false ,function ($plan_id) use ($query){
            $query->whereHas('plans' , function ($query) use ($plan_id){
               $query->where('plan_id',$plan_id);
            })
                ->with(['plans' => function ($query) use ($plan_id) {
                $query->where('plan_id', $plan_id)->select('plan_id', 'value');
            }]);
        });
//        if ($filters->isset('plan_id')) {
//             $query->whereHas('plans', function ($q) use ($planId) {
//                $q->where('plan_id', $planId);
//            })
//                ->with(['plans' => function ($q) use ($planId) {
//                    $q->where('plan_id', $planId)->select('plan_id', 'value');
//                }]);
//
//        }

        return $query;
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class,'plan__features')->withPivot('value');
    }
}
