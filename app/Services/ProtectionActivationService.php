<?php

namespace App\Services;


use App\Models\DeviceProtectionSetting;
use App\Models\ProtectionSyncLog;
use App\Models\Subscription;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class ProtectionActivationService
{


    public static function activate(
        Subscription $subscription
    ): void
    {


        DB::transaction(function() use(
            $subscription
        ){


            $device = $subscription->device;



            if(!$device){

                return;

            }




            /*
            |--------------------------------------------------------------------------
            | Enable Protection Setting
            |--------------------------------------------------------------------------
            */


            $setting =
                DeviceProtectionSetting::firstOrCreate(

                    [
                        'device_id'=>$device->id
                    ],

                    [

                        'betting_block'=>false,

                        'adult_content_block'=>false,

                        'facebook_ad_block'=>false,

                        'youtube_ad_block'=>false,

                        'safe_search'=>false,

                        'dns_protection'=>false,

                        'protection_status'=>'inactive',

                    ]

                );





            $setting->update([


                'protection_status'=>'active',


                'dns_protection'=>true,


                'safe_search'=>true,


                'last_sync_at'=>now(),


            ]);







            /*
            |--------------------------------------------------------------------------
            | Create Protection Sync Request
            |--------------------------------------------------------------------------
            */


            ProtectionSyncLog::create([


                'device_id'=>$device->id,


                'sync_version'=>1,


                'rules_hash'=>hash(
                    'sha256',
                    json_encode([
                        'dns_protection'=>true,
                        'safe_search'=>true,
                    ])
                ),


                'apply_status'=>'pending',


                'retry_count'=>0,


                'max_retry'=>3,


                'device_version'=>
                    $device->app_version,


            ]);



        });



    }


}