<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class SubscriptionEvent extends Model
{


    protected $fillable = [

        'subscription_id',

        'event',

        'old_status',

        'new_status',

        'description',

    ];




    public function subscription(): BelongsTo
    {

        return $this->belongsTo(
            Subscription::class
        );

    }


}