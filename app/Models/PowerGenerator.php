<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PowerGenerator extends Model
{
    protected $fillable = [
        'name',
        'location',
        'user_id'
    ];
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function phones()
    {
        return $this->hasMany(Phone::class);
    }
    public function areas()
    {
        return $this->hasMany(Area::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
