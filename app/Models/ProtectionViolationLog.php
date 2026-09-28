<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class ProtectionViolationLog extends Model
{


    protected $fillable = [

        'device_id',

        'rule_id',

        'domain',

        'category',

        'action',

    ];



    public function device(): BelongsTo
    {

        return $this->belongsTo(
            Device::class
        );

    }



    public function rule(): BelongsTo
    {

        return $this->belongsTo(
            ProtectionRule::class,
            'rule_id'
        );

    }


}