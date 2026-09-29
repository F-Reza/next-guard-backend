<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class ProtectionSyncLog extends Model
{


    protected $fillable = [


        'device_id',


        'sync_version',


        'rules_hash',


        'apply_status',


        'applied_at',


        'retry_count',


        'max_retry',


        'failure_reason',


        'last_retry_at',


        'device_version',


        'ip_address',


        'user_agent',


        'synced_at',


    ];





    protected function casts(): array
    {

        return [


            'synced_at'=>'datetime',


            'applied_at'=>'datetime',


            'last_retry_at'=>'datetime',


            'retry_count'=>'integer',


            'max_retry'=>'integer',


        ];

    }






    /*
    |--------------------------------------------------------------------------
    | Pending Sync
    |--------------------------------------------------------------------------
    */


    public function scopePending($query)
    {

        return $query->where(

            'apply_status',

            'pending'

        );

    }







    /*
    |--------------------------------------------------------------------------
    | Retryable Failed Sync
    |--------------------------------------------------------------------------
    */


    public function scopeRetryable($query)
    {

        return $query

            ->where(

                'apply_status',

                'failed'

            )

            ->whereColumn(

                'retry_count',

                '<',

                'max_retry'

            );

    }







    /*
    |--------------------------------------------------------------------------
    | Device Relation
    |--------------------------------------------------------------------------
    */


    public function device(): BelongsTo
    {


        return $this->belongsTo(

            Device::class

        );


    }



}