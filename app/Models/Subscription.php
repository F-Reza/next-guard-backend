<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{

    protected $fillable = [

        'user_id',

        'subscription_plan_id',

        'device_id',

        'starts_at',

        'expires_at',

        'status',

        'source',

        'payment_reference',

    ];



    protected function casts(): array
    {
        return [

            'starts_at' => 'datetime',

            'expires_at' => 'datetime',

        ];
    }



    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }



    public function plan(): BelongsTo
    {
        return $this->belongsTo(
            SubscriptionPlan::class,
            'subscription_plan_id'
        );
    }



    public function device(): BelongsTo
    {
        return $this->belongsTo(
            Device::class
        );
    }

}