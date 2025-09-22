<?php

namespace App\Models;

use App\ApiHelper\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SubscriptionRequest extends Model
{
        use HasFactory;
        use Translatable;

    protected $fillable = [
        'type',
        'period',
        'user_id',
        'planPrice_id',
        'location',
        'name',
        'status',
        'phones',
        'kiloPrice',
        'afterPaymentFrequency',
        'spendingType',
        'day',
    ];
    public $translatable=[
        'name',
        'location',
        'type',
        'status',
    ];

    protected $casts=[
        'phones' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function planPrice()
    {
        return $this->belongsTo(PlanPrice::class , 'planPrice_id');
    }

    public function payment()
    {
        return $this->hasOne(SubscriptionPayment::class,'subscriptionRequest_id');
    }
    public function scopeFilter($query,?string $type=null)
    {
        if($type)
        {
            return $query->where('type',$type);
        }
        return $query;

    }
    public function scopeStatus($query,array $filters)
    {
//        dd($filters['type']);
        $query->when($filters['status'] ?? false , function ($query) use ($filters){
            $query->where('status',$filters['status']);
        });
        return $query;
    }
}
