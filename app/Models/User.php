<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $guarded = array();

    protected $hidden = [
        'password',
        'business_name',
        'business_email',
        'business_phone_number',
        'business_image',
        'latitude',
        'longitude',
        'address',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function showroom()
    {
        return $this->belongsTo(Showroom::class, 'id', 'user_id');
    }

}
