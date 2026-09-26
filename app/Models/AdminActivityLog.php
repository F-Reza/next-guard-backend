<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class AdminActivityLog extends Model
{


    protected $fillable = [

        'admin_id',

        'action',

        'description',

        'severity',

        'ip_address',

        'user_agent',

        'device',

        'browser',

        'os',

        'session_id',

        'metadata',

    ];


    protected $casts = [

        'metadata'=>'array',

    ];



    public function admin(): BelongsTo
    {

        return $this->belongsTo(
            Admin::class
        );

    }


}