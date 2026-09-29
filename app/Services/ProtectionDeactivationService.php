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

                'last_sync_at'=>now(),

            ]);







            /*
            |--------------------------------------------------------------------------
            | Create Sync Request
            |--------------------------------------------------------------------------
            */


            $lastVersion = ProtectionSyncLog::where(
                'device_id',
                $device->id
            )
            ->lockForUpdate()
            ->max('sync_version');


            ProtectionSyncLog::create([

                'device_id'=>$device->id,

                'sync_version'=>($lastVersion ?? 0)+1,

                'rules_hash'=>hash(
                    'sha256',
                    'protection-disabled-'.$device->id.'-'.$reason
                ),

                'apply_status'=>'pending',

                'retry_count'=>0,

                'max_retry'=>3,

                'device_version'=>$device->app_version,

                'synced_at'=>null,

            ]);




        });


    }


}