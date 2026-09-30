<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PaymentWebhook extends Model
{


    protected $fillable = [

        'gateway',

        'event_id',

        'transaction_id',

        'payload',

        'status',

        'processed_at',

    ];



    protected function casts(): array
    {

        return [

            'payload'=>'array',

            'processed_at'=>'datetime',

        ];

    }


}