<?php

namespace App\Services;


use App\Models\Device;
use App\Models\ProtectionRule;
use App\Models\DeviceProtectionSetting;



class ProtectionEngineService
{


    /**
     * Generate protection payload for device
     */
    public static function payload(
        Device $device
    ): array
    {


        $settings = self::settings(
            $device
        );


        $rules = self::rules(
            $device
        );



        return [


            'device_id'=>$device->id,


            'protection'=>[

                'status'=>$settings?->protection_status 
                    ?? 'inactive',


                'betting_block'=>
                    (bool)($settings?->betting_block ?? false),


                'adult_content_block'=>
                    (bool)($settings?->adult_content_block ?? false),


                'facebook_ad_block'=>
                    (bool)($settings?->facebook_ad_block ?? false),


                'youtube_ad_block'=>
                    (bool)($settings?->youtube_ad_block ?? false),


                'safe_search'=>
                    (bool)($settings?->safe_search ?? false),


                'dns_protection'=>
                    (bool)($settings?->dns_protection ?? false),


            ],



            'rules'=>$rules->values(),



            'synced_at'=>now(),


        ];

    }








    /**
     * Get device protection settings
     */
    private static function settings(
        Device $device
    ): ?DeviceProtectionSetting
    {


        return DeviceProtectionSetting::where(

            'device_id',

            $device->id

        )->first();


    }









    /**
     * Get active rules
     */
    private static function rules(
        Device $device
    )
    {


        return ProtectionRule::where(

            function($query) use($device){


                $query

                ->whereNull(
                    'device_id'
                )

                ->orWhere(
                    'device_id',
                    $device->id
                );


            }

        )

        ->where(

            'status',

            'active'

        )

        ->get()

        ->map(function($rule){


            return [

                'id'=>$rule->id,

                'category'=>$rule->category,

                'domain'=>$rule->domain,

                'rule_type'=>$rule->rule_type,

                'description'=>$rule->description,


            ];


        })

        ->values();


    }




}