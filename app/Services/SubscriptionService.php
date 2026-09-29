<?php

namespace App\Services;


use App\Models\Subscription;
use App\Models\User;
use App\Models\SubscriptionEvent;


class SubscriptionService
{


    /**
     * Check subscription active
     */
    public static function isActive(
        ?Subscription $subscription
    ): bool
    {


        if(!$subscription){

            return false;

        }



        return
            $subscription->status === 'active'
            &&
            (
                !$subscription->expires_at
                ||
                $subscription->expires_at->isFuture()
            );


    }






    /**
     * Get current subscription
     */
    public static function current(
        User $user
    ): ?Subscription
    {


        return $user
            ->subscriptions()
            ->latest('id')
            ->first();


    }






    /**
     * Activate subscription
     */
    public static function activate(
        Subscription $subscription
    ): Subscription
    {


        $subscription->update([

            'status'=>'active',

            'starts_at'=>
                $subscription->starts_at
                ??
                now(),

        ]);



        return $subscription->fresh();


    }






    /**
     * Expire subscription
     */
    public static function expire(
        Subscription $subscription
    ): Subscription
    {


        $oldStatus = $subscription->status;


        $subscription->update([

            'status'=>'expired'

        ]);




        SubscriptionEvent::create([

            'subscription_id'=>
                $subscription->id,

            'event'=>
                'expired',

            'old_status'=>
                $oldStatus,

            'new_status'=>
                'expired',

            'description'=>
                'Subscription expired automatically.'

        ]);



        return $subscription->fresh();


    }



}