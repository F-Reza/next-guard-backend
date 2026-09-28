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




    

    /**
     * Check domain protection decision
     */
    public static function checkDomain(
        Device $device,
        string $domain
    ): array
    {

        $settings = self::settings(
            $device
        );


        if(!$settings ||
            $settings->protection_status !== 'active'
        ){

            return [

                'allowed'=>true,

                'reason'=>'Protection inactive.'

            ];

        }



        $domain = strtolower(
            trim($domain)
        );


        $domain = preg_replace(
            '/^www\./',
            '',
            $domain
        );




        $rule = ProtectionRule::where(

            function($query) use($device){

                $query
                    ->whereNull('device_id')
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
        ->where(function($query) use($domain){

            $query
                ->where(
                    'domain',
                    $domain
                )
                ->orWhereRaw(
                    "? LIKE CONCAT('%.' , domain)",
                    [$domain]
                );

        })
        ->first();





        if(!$rule){

            return [

                'allowed'=>true,

                'domain'=>$domain

            ];

        }





        $blocked = match($rule->category){


            'betting',
            'gambling'
                =>
                $settings->betting_block,


            'adult'
                =>
                $settings->adult_content_block,


            'youtube_ads'
                =>
                $settings->youtube_ad_block,


            'facebook_ads'
                =>
                $settings->facebook_ad_block,


            default =>
                false,


        };






        return [

            'allowed'=>!$blocked,

            'domain'=>$domain,

            'category'=>$rule->category,

            'rule_id'=>$rule->id,

            'reason'=>
                $blocked

                ? $rule->description ?? 'Blocked by protection rule.'

                : 'Rule found but protection disabled.',

        ];

    }





}