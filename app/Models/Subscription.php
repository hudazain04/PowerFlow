<?php

namespace App\Models;

use App\Types\SubscriptionExpirationTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Subscription extends Model
{
     use HasFactory;

    protected $fillable = [
        'provider_id',
        'start_time',
        'price',
        'period'
    ];

    protected $casts=[
        'start_time' => 'date',
    ];
    public function serviceprovider()
    {
        return $this->belongsTo(PowerGenerator::class);
    }

    public function planPrice()
    {
        return $this->belongsTo(PlanPrice::class,'planPrice_id');
    }

    public function scopeFilter($query, ?string $type=null)
    {

        if ($type==SubscriptionExpirationTypes::Active)
        {
//            $query->whereDate('start_time', '>=', Carbon::now()->subMonths($this->period));
            $query->whereRaw("DATE_ADD(start_time, INTERVAL period MONTH) >= ?", [Carbon::now()]);
        }

        elseif ($type==SubscriptionExpirationTypes::Expired)
        {
//            $query->whereDate('start_time', '<=', Carbon::now()->subMonths($this->period));
            $query->whereRaw("DATE_ADD(start_time, INTERVAL period MONTH) < ?", [Carbon::now()]);

        }

        return $query;
    }

}
