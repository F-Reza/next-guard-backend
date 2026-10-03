<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Payment extends Model
{
    protected $fillable = [

        'user_id',

        'subscription_id',

        'gateway',

        'provider',

        'transaction_id',

        'provider_transaction_id',

        'amount',

        'currency',

        'status',

        'gateway_response',

        'paid_at',

        'verified_at',

    ];


    protected function casts(): array
    {
        return [

            'amount' =>
                'decimal:2',

            'gateway_response' =>
                'array',

            'paid_at' =>
                'datetime',

            'verified_at' =>
                'datetime',

        ];
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }


    public function subscription(): BelongsTo
    {
        return $this->belongsTo(
            Subscription::class
        );
    }
}