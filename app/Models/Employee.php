<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;


class Employee extends Authenticate
{
<<<<<<< HEAD
        use HasFactory;
=======
        use HasFactory,HasApiTokens;
>>>>>>> origin/huda

    protected $fillable = [
        'phone_number',
        'first_name',
        'last_name',
        'secret_key',
        'generator_id',
        'user_id',
    ];
    public function powergenerator()
    {
        return $this->belongsTo(PowerGenerator::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function generateSecretKey(): string
    {
        $key = bin2hex(random_bytes(2));
        $this->update([
            'secret_key' => $key,
        ]);
        return $key;
    }
}
