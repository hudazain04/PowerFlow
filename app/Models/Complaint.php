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
    protected $guarded=['id'];
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
//        dd($filters['type']);
        $query->when($filters['type'] ?? false , function ($query) use ($filters){
            $query->where('type',$filters['type']);
        });
        return $query;
    }
}
