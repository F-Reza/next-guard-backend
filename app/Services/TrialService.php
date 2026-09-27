<?php

namespace App\Services;


use App\Models\User;
use App\Models\Device;
use App\Models\TrialEntitlement;
use App\Models\TrialEvent;

use Illuminate\Support\Facades\DB;



class TrialService
{


    /*
    |--------------------------------------------------------------------------
    | Base Trial Duration
    |--------------------------------------------------------------------------
    */

    private const TRIAL_DAYS = 3;





    /**
     * Check Trial Eligibility
     */
    public static function eligibility(
        User $user,
        Device $device
    ): array
    {


        $trial =
            TrialEntitlement::where('user_id',$user->id)
            ->where('device_id',$device->id)
            ->first();



        if(!$trial){


            return [

                'eligible'=>true,

                'device_id'=>$device->id,

                'status'=>'eligible'

            ];


        }





        self::syncExpiredStatus($trial);




        return [

            'eligible'=>false,

            'device_id'=>$device->id,

            'status'=>$trial->status,

            'started_at'=>$trial->started_at,

            'expires_at'=>$trial->expires_at

        ];



    }









    /**
     * Start Trial
     */
    public static function start(
        User $user,
        Device $device
    ): array
    {



        return DB::transaction(function()
        use(
            $user,
            $device
        ){



            $existing =
                TrialEntitlement::where(
                    'user_id',
                    $user->id
                )
                ->where(
                    'device_id',
                    $device->id
                )
                ->lockForUpdate()
                ->first();




            if($existing){



                self::syncExpiredStatus(
                    $existing
                );



                return [

                    'created'=>false,

                    'trial'=>$existing

                ];


            }





            $startedAt = now();


            $expiresAt =
                $startedAt
                ->copy()
                ->addDays(
                    self::TRIAL_DAYS
                );






            $trial =
                TrialEntitlement::create([


                    'user_id'=>$user->id,


                    'device_id'=>$device->id,


                    'started_at'=>$startedAt,


                    'expires_at'=>$expiresAt,


                    'status'=>'active',


                    'base_trial'=>true,


                ]);







            TrialEvent::create([


                'user_id'=>$user->id,


                'device_id'=>$device->id,


                'event_type'=>'TRIAL_STARTED',


                'old_expires_at'=>null,


                'new_expires_at'=>$expiresAt,


                'reason'=>'Base trial started.',


                'admin_id'=>null,


            ]);







            return [


                'created'=>true,


                'trial'=>$trial


            ];



        });



    }









    /**
     * Current Trial
     */
    public static function current(
        User $user,
        Device $device
    ): ?TrialEntitlement
    {



        $trial =
            TrialEntitlement::where(
                'user_id',
                $user->id
            )
            ->where(
                'device_id',
                $device->id
            )
            ->first();




        if($trial){


            self::syncExpiredStatus(
                $trial
            );


        }



        return $trial;



    }









    /**
     * Auto Expire
     */
    private static function syncExpiredStatus(
        TrialEntitlement $trial
    ): void
    {



        if(
            $trial->status !== 'active'
            ||
            !$trial->expires_at
            ||
            !$trial->expires_at->isPast()
        ){

            return;

        }






        $oldExpiresAt =
            $trial->expires_at;





        $trial->update([

            'status'=>'expired'

        ]);






        TrialEvent::create([


            'user_id'=>$trial->user_id,


            'device_id'=>$trial->device_id,


            'event_type'=>'TRIAL_EXPIRED',


            'old_expires_at'=>$oldExpiresAt,


            'new_expires_at'=>$oldExpiresAt,


            'reason'=>'Trial expired automatically.',


            'admin_id'=>null,


        ]);



    }





}