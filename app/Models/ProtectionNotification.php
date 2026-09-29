<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ProtectionNotification extends Model
{


    protected $fillable = [

        'device_id',

        'user_id',

        'type',

        'title',

        'message',

        'domain',

        'category',

        'read_at',

    ];



    protected $casts = [

        'read_at'=>'datetime'

    ];



    public function device(): BelongsTo
    {

        return $this->belongsTo(
            Device::class
        );

    }



    public function user(): BelongsTo
    {

        return $this->belongsTo(
            User::class
        );

    }


}