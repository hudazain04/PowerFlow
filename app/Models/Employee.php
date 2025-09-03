<?php

namespace App\Models;

use App\ApiHelper\HasFeatureLimit;
use App\ApiHelper\Translatable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Laravel\Sanctum\HasApiTokens;


class Employee extends Authenticate
{

    use HasFactory,HasFeatureLimit;

    protected $fillable = [
        'user_name',
        'secret_key',
        'generator_id',
        'phone_number',
    ];
    public string $featureKey = 'employees_count';

    public function powerGenerator()
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
