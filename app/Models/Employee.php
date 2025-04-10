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
        'provider_id',
        'phone'
    ];
    public function serviceprovider()
    {
        return $this->belongsTo(PowerGenerator::class);
    }
}
