<?php

namespace App\Models;

 use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject,MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */

    use HasFactory, Notifiable,HasRoles,\Illuminate\Auth\MustVerifyEmail;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
    ];



    protected $guard_name = 'api';


    public function fullName()
    {
        return $this->first_name . ' '. $this->last_name;
    }

    public function subcriptionrequest()
    {
        return $this->hasMany(SubscriptionRequest::class);
    }
    public function powerGenerator()
    {
        return $this->hasOne(PowerGenerator::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

//    public function subcriptionrequest()
//    {
//        return $this->hasMany(SubscriptionRequest::class);
//    }
//    public function powerGenerators()
//    {
//        return $this->hasMany(PowerGenerator::class);
//    }

    public function faqs()
    {
        return $this->belongsToMany(Faq::class);
    }
    public function generatorRequest(){
        return $this->BelongsTo(GeneratorRequest::class);
    }
    public function customerRequests(){
        return $this->hasMany(CustomerRequest::class);
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'roles' => $this->getRoleNames()->toArray(),
        ];
    }
}
