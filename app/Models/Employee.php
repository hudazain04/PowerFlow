<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticate;


class Employee extends Authenticate
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'generator_id',
        'phone_number',
        'secret_key',
        'password',
        'user_id'
    ];
    public function serviceprovider()
    {
        return $this->belongsTo(PowerGenerator::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
