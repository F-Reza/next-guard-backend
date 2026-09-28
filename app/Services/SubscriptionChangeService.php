<?php

namespace App\Services;


use App\Models\User;

use App\Models\Subscription;

use App\Models\SubscriptionPlan;

use App\Models\SubscriptionEvent;

use Illuminate\Support\Facades\DB;



class SubscriptionChangeService
{


    /**
     * Change subscription plan
     */
    public static function changePlan(
        User $user,
        SubscriptionPlan $newPlan
    ): Subscription
    {


        return DB::transaction(function() use(
            $user,
            $newPlan
        ){




            /*
            |--------------------------------------------------------------------------
            | Current Subscription
            |--------------------------------------------------------------------------
            */


            $oldSubscription = $user
                ->subscriptions()
                ->where(
                    'status',
                    'active'
                )
                ->latest()
                ->first();





            /*
            |--------------------------------------------------------------------------
            | Expire Old Subscription
            |--------------------------------------------------------------------------
            */


            if($oldSubscription){


                $oldStatus = 
                    $oldSubscription->status;



                $oldSubscription->update([

                    'status'=>'changed'

                ]);



                SubscriptionEvent::create([

                    'subscription_id'=>
                        $oldSubscription->id,


                    'event'=>
                        'plan_changed',


                    'old_status'=>
                        $oldStatus,


                    'new_status'=>
                        'changed',


                    'description'=>
                        'Subscription plan changed.'

                ]);

            }







            /*
            |--------------------------------------------------------------------------
            | Create New Subscription
            |--------------------------------------------------------------------------
            */


            $subscription = Subscription::create([


                'user_id'=>$user->id,


                'subscription_plan_id'=>$newPlan->id,


                'device_id'=>
                    $oldSubscription?->device_id,


                'starts_at'=>now(),


                'expires_at'=>now()->addDays(
                    $newPlan->duration_days
                ),


                'status'=>'active',


                'source'=>'upgrade',


            ]);







            /*
            |--------------------------------------------------------------------------
            | Event Log
            |--------------------------------------------------------------------------
            */


            SubscriptionEvent::create([


                'subscription_id'=>
                    $subscription->id,


                'event'=>
                    'activated',


                'old_status'=>
                    null,


                'new_status'=>
                    'active',


                'description'=>
                    'New subscription activated.'

            ]);







            return $subscription;



        });



    }



}