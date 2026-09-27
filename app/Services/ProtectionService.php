<?php

namespace App\Services;


use App\Models\Device;
use App\Models\DeviceProtectionSetting;

use Illuminate\Support\Facades\DB;

use Throwable;



class ProtectionService
{


    /**
     * Get protection settings
     */
    public static function get(
        Device $device
    ): DeviceProtectionSetting
    {


        return DeviceProtectionSetting::firstOrCreate(

            [
                'device_id'=>$device->id,
            ],


            [

                'betting_block'=>false,

                'adult_content_block'=>false,

                'facebook_ad_block'=>false,

                'youtube_ad_block'=>false,

                'safe_search'=>false,

                'dns_protection'=>false,

                'protection_status'=>'inactive',

                'last_sync_at'=>null,

            ]

        );


    }







    /**
     * Update protection settings
     */
    public static function update(
        Device $device,
        array $data
    ): DeviceProtectionSetting
    {


        return DB::transaction(function() use(
            $device,
            $data
        ){


            $settings = self::get(
                $device
            );



            $settings->update([


                'betting_block'=>
                    $data['betting_block']
                    ?? $settings->betting_block,


                'adult_content_block'=>
                    $data['adult_content_block']
                    ?? $settings->adult_content_block,


                'facebook_ad_block'=>
                    $data['facebook_ad_block']
                    ?? $settings->facebook_ad_block,


                'youtube_ad_block'=>
                    $data['youtube_ad_block']
                    ?? $settings->youtube_ad_block,


                'safe_search'=>
                    $data['safe_search']
                    ?? $settings->safe_search,


                'dns_protection'=>
                    $data['dns_protection']
                    ?? $settings->dns_protection,


                'protection_status'=>'active',


                'last_sync_at'=>now(),


            ]);




            return $settings;


        });


    }









    /**
     * Sync device protection
     */
    public static function sync(
        Device $device
    ): DeviceProtectionSetting
    {


        $settings = self::get(
            $device
        );



        $settings->update([


            'last_sync_at'=>now(),


            'protection_status'=>'active',


        ]);



        return $settings;


    }





}