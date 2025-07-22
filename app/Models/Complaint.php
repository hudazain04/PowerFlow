<?php

namespace App\Models;

use App\Types\ComplaintTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Complaint extends Model
{

    use HasFactory;

    protected $fillable = [
        'description',
        'status',
        'counter_id',
        'user_id',
        'type',

    ];

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }

    public function scopeFilter($query,array $filters)
    {
//        dd($filters['type']);
        $query->when($filters['type'] ?? false , function ($query) use ($filters){
            $query->where('type',$filters['type']);
        });
        return $query;
    }
}
