<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class AdminNotification extends Model
{


        protected $fillable = [

            'admin_id',
            'type',
            'title',
            'message',
            'is_read',
            'metadata',

        ];


        protected $casts = [

            'is_read'=>'boolean',

            'metadata'=>'array',

        ];



    public function admin(): BelongsTo
    {

        return $this->belongsTo(
            Admin::class
        );

    }


}