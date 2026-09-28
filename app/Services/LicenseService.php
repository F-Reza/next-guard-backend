<?php

namespace App\Services;


use App\Models\User;
use App\Models\Device;
use App\Models\LicenseCode;
use App\Models\Subscription;
use App\Services\ProtectionActivationService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;



class LicenseService
{


    /**
     * Generate License Code
     */
    public static function generate(
        int $planId,
        int $durationDays
    ): LicenseCode
    {


        return LicenseCode::create([


            'code'=>
                'NG-'
                .
                strtoupper(
                    Str::random(10)
                ),


            'subscription_plan_id'=>
                $planId,


            'duration_days'=>
                $durationDays,


            'status'=>
                'active',


        ]);


    }







    /**
     * Redeem License
     */
    public static function redeem(
        User $user,
        LicenseCode $license,
        ?Device $device = null
    ): Subscription
    {


        return DB::transaction(function() use(
            $user,
            $license,
            $device
        ){



            /*
            |--------------------------------------------------------------------------
            | Validate License
            |--------------------------------------------------------------------------
            */


            if(
                $license->status !== 'active'
            ){

                throw new Exception(
                    'License already used or inactive.'
                );

            }





            /*
            |--------------------------------------------------------------------------
            | Create Subscription
            |--------------------------------------------------------------------------
            */


            $subscription = Subscription::create([


                'user_id'=>
                    $user->id,


                'subscription_plan_id'=>
                    $license->subscription_plan_id,


                'device_id'=>
                    $device?->id,


                'starts_at'=>
                    now(),


                'expires_at'=>
                    now()->addDays(
                        $license->duration_days
                    ),


                'status'=>
                    'active',


                'source'=>
                    'license',


            ]);







            /*
            |--------------------------------------------------------------------------
            | Mark License Used
            |--------------------------------------------------------------------------
            */


            $license->update([


                'status'=>
                    'used',


                'used_by'=>
                    $user->id,


                'used_device_id'=>
                    $device?->id,


                'used_at'=>
                    now(),


            ]);

            
            ProtectionActivationService::activate(
                $subscription
            );





            /*
            |--------------------------------------------------------------------------
            | Return Subscription
            |--------------------------------------------------------------------------
            */


            return $subscription;


        });


    }





}