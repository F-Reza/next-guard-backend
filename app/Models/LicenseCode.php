<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseCode extends Model
{

    protected $fillable = [

        'code',

        'subscription_plan_id',

        'duration_days',

        'status',

        'used_by',

        'used_device_id',

        'used_at',

    ];



    protected function casts(): array
    {
        return [

            'used_at' => 'datetime',

        ];
    }



    /**
     * Subscription plan
     */
    public function plan(): BelongsTo
    {

        return $this->belongsTo(
            SubscriptionPlan::class,
            'subscription_plan_id'
        );

    }



    /**
     * User who redeemed code
     */
    public function user(): BelongsTo
    {

        return $this->belongsTo(
            User::class,
            'used_by'
        );

    }



    /**
     * Device used for activation
     */
    public function device(): BelongsTo
    {

        return $this->belongsTo(
            Device::class,
            'used_device_id'
        );

    }

}