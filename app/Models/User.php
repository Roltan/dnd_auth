<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'token'
    ];

    protected $hidden = [
        'password',
    ];

    // Для JWT-auth:
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [
            'exp' => now()->addMinutes(config('jwt.access_ttl'))->timestamp, // access token expiry
            'refresh_exp' => now()->addMinutes(config('jwt.refresh_ttl'))->timestamp // refresh token expiry
        ];
    }
}
