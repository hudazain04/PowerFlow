<?php

namespace App\Models;

use App\ApiHelper\Translatable;
use App\Types\SubscriptionExpirationTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PowerGenerator extends Model
{
    use HasFactory;
    use Translatable;

    protected $fillable = [
        'name',
        'location',
        'user_id'
    ];
    public $translatable=[
        'name',
        'location'
    ];
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class,'generator_id');
    }
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function phones()
    {
        return $this->hasMany(Phone::class, 'generator_id');
    }
    public function areas()
    {
        return $this->hasMany(Area::class);
    }
    public function electricalBoxes(){
        return $this->hasMany(ElectricalBox::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function scopePlanGenerators($query,array $filters)
    {
        $query->when($filters['search'] ?? false ,function ($query) use ($filters){
            $search=$filters['search'];
           $query->where(function ($query) use ($search){
               foreach ($this->getFillable() as $column)
               {
                   ($query->orWhere($column,'like',"%$search%"));
               }

               $query->orWhereHas('user', function ($q) use ($search) {
                   $q->where('phone_number', 'like', "%$search%");
               });

               $query->orWhereHas('subscriptions', function ($q) use ($search) {
                   $q->whereRaw("DATE_FORMAT(DATE_ADD(start_time, INTERVAL period MONTH), '%Y-%m-%d') LIKE ?", ["%$search%"]);
               });
           });
        });

        $query->when($filters['status'] ?? false , function ($query) use ($filters){
            if ($filters['status'] == SubscriptionExpirationTypes::Active)
            {
                $query->whereRelation('subscriptions', function ($query) {
                    $query->whereRaw("DATE_ADD(start_time, INTERVAL period MONTH) >= ?", [Carbon::now()]);
                });

            }
            elseif ($filters['status']==SubscriptionExpirationTypes::Expired)
            {
                $query->whereRelation('subscriptions', function ($query) {
                    $query->whereRaw("DATE_ADD(start_time, INTERVAL period MONTH) < ?", [Carbon::now()]);
                });            }
        });
        return $query;
    }


    public function scopeFilter($query,array $filters)
    {
        $query->when($filters['search'] ?? false ,function ($query) use ($filters){
            $search=$filters['search'];
            $query->where(function ($query) use ($search){
                foreach ($this->getFillable() as $column)
                {
                    ($query->orWhere($column,'like',"%$search%"));
                }

                $query->orWhereHas('user', function ($q) use ($search) {
                    $q->where('phone_number', 'like', "%$search%");
                });

                $query->orWhereHas('subscriptions', function ($q) use ($search) {
                    $q->whereRaw("DATE_FORMAT(DATE_ADD(start_time, INTERVAL period MONTH), '%Y-%m-%d') LIKE ?", ["%$search%"]);
                });
            });
        });


        return $query;
    }
}
