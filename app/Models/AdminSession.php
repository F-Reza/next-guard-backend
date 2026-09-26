<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class AdminSession extends Model
{


    protected $fillable = [


        'admin_id',

        'token_hash',

        'ip_address',

        'user_agent',

        'last_activity',

        'expires_at'


    ];



    protected $hidden = [

        'token_hash',

    ];



    protected $casts = [


        'last_activity'=>'datetime',

        'expires_at'=>'datetime'


    ];




    public function admin(): BelongsTo
    {

        return $this->belongsTo(
            Admin::class
        );

    }


}