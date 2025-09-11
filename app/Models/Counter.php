<?php

namespace App\Models;

use App\ApiHelper\GenerateCounterNumber;
use App\ApiHelper\HasFeatureLimit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Counter extends Model
{
     use HasFactory,HasFeatureLimit;
    use GenerateCounterNumber;

    protected $fillable = [
        'number',
        'QRCode',
        'current_spending',
        'user_id',
        'generator_id',
        'status'
    ];

    public string $featureKey = 'counters_count';

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
    public function spendings()
    {
        return $this->hasMany(Spending::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
   public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function electricalBoxes()
    {
        return $this->belongsToMany(ElectricalBox::class, 'counter__boxes', 'counter_id',
            'box_id')
            ->withPivot(['installed_at', 'removed_at'])
            ->wherePivotNull('removed_at');

    }
    public function powerGenerator()
    {
        return $this->belongsTo(PowerGenerator::class,'generator_id');
    }
    public function scopeFilter($query,array $filters)
    {
//        dd($filters['type']);
        $query->when($filters['status'] ?? false , function ($query) use ($filters){
            $query->where('status',$filters['status']);
        });
        return $query;
    }
//    public function boxes(){
//        return $this->hasMany(ElectricalBox::class);
//    }

}
