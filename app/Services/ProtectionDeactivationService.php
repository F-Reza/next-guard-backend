<?php

namespace App\Services;


use App\Models\Subscription;
use App\Models\DeviceProtectionSetting;
use App\Models\ProtectionSyncLog;

use Illuminate\Support\Facades\DB;



class ProtectionDeactivationService
{


    public static function deactivate(
        Subscription $subscription,
        string $reason = 'subscription_expired'
    ): void
    {


        DB::transaction(function() use(
            $subscription,
            $reason
        ){



            $device = $subscription->device;



            if(!$device){

                return;

            }





            /*
            |--------------------------------------------------------------------------
            | Disable Protection
            |--------------------------------------------------------------------------
            */


            DeviceProtectionSetting::where(
                'device_id',
                $device->id
            )
            ->update([

                'protection_status'=>'inactive',

                'disabled_reason'=>$reason,

                'betting_block'=>false,

                'adult_content_block'=>false,

                'facebook_ad_block'=>false,

                'youtube_ad_block'=>false,

                'safe_search'=>false,

                'dns_protection'=>false,

                'last_sync_at'=>now(),

            ]);







            /*
            |--------------------------------------------------------------------------
            | Create Sync Request
            |--------------------------------------------------------------------------
            */


            ProtectionSyncLog::create([


                'device_id'=>$device->id,


                'sync_version'=>
                    (ProtectionSyncLog::where(
                        'device_id',
                        $device->id
                    )->max('sync_version') ?? 0) + 1,


                'rules_hash'=>
                    hash(
                        'sha256',
                        'protection-disabled-'.$device->id.'-'.$reason
                    ),


                'apply_status'=>'pending',


                'synced_at'=>null,


            ]);




        });


    }


}