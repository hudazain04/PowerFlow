<?php

namespace App\Models;

use App\ApiHelper\HasFeatureLimit;
use App\ApiHelper\Translatable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

//use Laravel\Sanctum\HasApiTokens;


class Employee extends Authenticate implements JWTSubject
{
    use HasFactory, HasPermissions,HasRoles,HasFeatureLimit;

    protected $guard_name = 'employee';

    protected $fillable = [
        'user_name',
        'secret_key',
        'generator_id',
        'phone_number',
        'area_id'
    ];
    public string $featureKey = 'employees_count';
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function getAuthPassword()
    {
        return $this->secret_key;
    }

    public function powerGenerator()
    {
        return $this->belongsTo(PowerGenerator::class,'generator_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function generateSecretKey(): string
    {
        $key = bin2hex(random_bytes(4));
        $this->update([
            'secret_key' => $key,
        ]);
        return $key;
    }
}
