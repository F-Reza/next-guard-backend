<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Device extends Model
{
    protected $fillable = [
        'user_id',
        'device_uuid_hash',
        'platform',
        'model',
        'manufacturer',
        'android_version',
        'app_version',
        'management_mode',
        'status',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'last_seen_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deviceSessions(): HasMany
    {
        return $this->hasMany(DeviceSession::class);
    }


    public function trialEntitlements(): HasMany
    {
        return $this->hasMany(
            TrialEntitlement::class
        );
    }

    public function protectionSetting(): HasOne
    {
        return $this->hasOne(
            DeviceProtectionSetting::class
        );
    }

    /**
     * Get the trial events associated with the device.
     */
    public function trialEvents(): HasMany
    {
        return $this->hasMany(TrialEvent::class);
    }

    
    public function subscriptions(): HasMany
    {
        return $this->hasMany(
            Subscription::class
        );
    }
    

}