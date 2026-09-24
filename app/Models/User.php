<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * JWT identifier.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * JWT custom claims.
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'type' => 'access',
        ];
    }

    /**
     * User devices.
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Device sessions.
     */
    public function deviceSessions()
    {
        return $this->hasMany(DeviceSession::class);
    }

    /**
     * Trial entitlements.
     */
    public function trialEntitlements()
    {
        return $this->hasMany(TrialEntitlement::class);
    }

    /**
     * Trial events.
     */
    public function trialEvents(): HasMany
    {
        return $this->hasMany(TrialEvent::class);
    }

}