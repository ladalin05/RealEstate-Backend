<?php

namespace App\Models\UserManagement;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes;

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'profile_picture',
        'gender',
        'dob',
        'otp',
        'otp_expires_at',
        'google_id',
        'telegram_id',
        'telegram_username',
        'is_verify_google',
        'is_verify_telegram',
        'is_verify_email',
        'is_verify_phone',
        'active',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'otp_expires_at'     => 'datetime',
            'dob'                => 'datetime',
            'password'           => 'hashed',
            'is_verify_google'   => 'boolean',
            'is_verify_telegram' => 'boolean',
            'is_verify_email'    => 'boolean',
            'is_verify_phone'    => 'boolean',
            'active'             => 'boolean',
        ];
    }

    /**
     * JWTSubject: the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * JWTSubject: custom claims to add to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}