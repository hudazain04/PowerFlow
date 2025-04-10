<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
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
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
