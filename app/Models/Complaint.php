<?php

namespace App\Models;

use App\ApiHelper\Translatable;
use App\Types\ComplaintTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Complaint extends Model
{

    use HasFactory;
    use Translatable;
    protected $fillable=[
        'type',
        'status',
        'counter_id',
        'description',
        'employee_id',
        'user_id',
        'translation',
    ];
    public $translatable=[
        'description',
        'status',
    ];

    public function counter()
    {
        return $this->belongsTo(Counter::class);
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

            });
        });
//        dd($filters['type']);
        $query->when($filters['type'] ?? false , function ($query) use ($filters){
            $query->where('type',$filters['type']);
        });
        $query->when($filters['status'] ?? false , function ($query) use ($filters){
            $query->where('status',$filters['status']);
        });
        return $query;
    }
}
