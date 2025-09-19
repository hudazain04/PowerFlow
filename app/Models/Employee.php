<?php

namespace App\Models;

use App\ApiHelper\HasFeatureLimit;
use App\ApiHelper\Translatable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

//use Laravel\Sanctum\HasApiTokens;


class Employee extends Authenticate implements JWTSubject
{
    use HasFactory, HasPermissions,HasRoles,HasFeatureLimit,Notifiable;

    protected $guard_name = 'employee';

    protected $fillable = [
        'first_name',
        'last_name',
        'secret_key',
        'generator_id',
        'phone_number',
        'area_id'
    ];
    protected $casts = [
        'secret_key',

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
        $Key = bin2hex(random_bytes(4));
        $hashedKey = Hash::make($Key);

        $this->update([
            'secret_key' => $hashedKey,
        ]);

        return $hashedKey;
    }
    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
}
