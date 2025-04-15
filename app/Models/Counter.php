<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Counter extends Model
{
     use HasFactory;

   
    protected $fillable = [
        'number',
        'QRCode',
        'subscriber_id'
    ];

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
    public function spendings()
    {
        return $this->hasMany(Spending::class);
    }
    public function paymentes()
    {
        return $this->hasMany(Payment::class);
    }
   public function user()
    {
        return $this->belongsTo(User::class);
    }
}
