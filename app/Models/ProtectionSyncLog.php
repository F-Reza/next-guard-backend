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

        'ip_address',

        'user_agent',

        'synced_at',

    ];




    protected function casts(): array
    {

        return [

            'synced_at'=>'datetime',

        ];

    }





    public function device(): BelongsTo
    {

        return $this->belongsTo(
            Device::class
        );

    }



}