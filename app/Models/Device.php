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

        'name',

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








    /*
    |--------------------------------------------------------------------------
    | User Relation
    |--------------------------------------------------------------------------
    */


    public function user(): BelongsTo
    {

        return $this->belongsTo(
            User::class
        );

    }








    /*
    |--------------------------------------------------------------------------
    | Device Sessions
    |--------------------------------------------------------------------------
    */


    public function deviceSessions(): HasMany
    {

        return $this->hasMany(
            DeviceSession::class
        );

    }








    /*
    |--------------------------------------------------------------------------
    | Trial Relations
    |--------------------------------------------------------------------------
    */


    public function trialEntitlements(): HasMany
    {

        return $this->hasMany(
            TrialEntitlement::class
        );

    }





    public function trialEvents(): HasMany
    {

        return $this->hasMany(
            TrialEvent::class
        );

    }








    /*
    |--------------------------------------------------------------------------
    | Protection Relations
    |--------------------------------------------------------------------------
    */


    public function protectionSetting(): HasOne
    {

        return $this->hasOne(
            DeviceProtectionSetting::class
        );

    }





    public function protectionSyncLogs(): HasMany
    {

        return $this->hasMany(
            ProtectionSyncLog::class
        );

    }








    /*
    |--------------------------------------------------------------------------
    | Subscription Relations
    |--------------------------------------------------------------------------
    */


    public function subscriptions(): HasMany
    {

        return $this->hasMany(
            Subscription::class
        );

    }








    /*
    |--------------------------------------------------------------------------
    | License Relations
    |--------------------------------------------------------------------------
    */


    public function licenseCodes(): HasMany
    {

        return $this->hasMany(
            LicenseCode::class,
            'used_device_id'
        );

    }








    /*
    |--------------------------------------------------------------------------
    | Device Status Helpers
    |--------------------------------------------------------------------------
    */


    public function isActive(): bool
    {

        return $this->status === 'active';

    }





    public function isRevoked(): bool
    {

        return $this->status === 'revoked';

    }








    /*
    |--------------------------------------------------------------------------
    | Device Session Helpers
    |--------------------------------------------------------------------------
    */


    public function revokeSessions(): void
    {

        $this->deviceSessions()
            ->update([
                'status'=>'revoked'
            ]);

    }



}