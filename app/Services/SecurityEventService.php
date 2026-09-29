<?php

namespace App\Services;


use App\Models\DeviceEvent;


class SecurityEventService
{


    /**
     * Create device security event
     */
    public static function create(
        int $deviceId,
        string $event
    ): DeviceEvent
    {


        return DeviceEvent::create([

            'device_id'=>$deviceId,

            'event'=>$event,

        ]);

    }



}