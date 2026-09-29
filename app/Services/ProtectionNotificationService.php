<?php

namespace App\Services;


use App\Models\ProtectionNotification;


class ProtectionNotificationService
{


    public static function create(
        $device,
        array $result
    ): ProtectionNotification
    {


        return ProtectionNotification::create([


            'device_id'=>$device->id,


            'user_id'=>$device->user_id,


            'type'=>'protection_alert',


            'title'=>'Blocked website detected',


            'message'=>
                $result['domain']
                .' was blocked by protection.',


            'domain'=>
                $result['domain']
                ?? null,


            'category'=>
                $result['category']
                ?? null,


        ]);

    }


}