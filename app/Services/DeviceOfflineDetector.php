<?php 

namespace App\Services; 


use App\Models\Device;
use App\Models\DeviceEvent;


class DeviceOfflineDetector
{


    /**
     * Mark inactive devices offline
     */
    public static function detect(): int
    {


        $devices = Device::where(
                'status',
                'active'
            )
            ->where(
                'last_seen_at',
                '<',
                now()->subMinutes(10)
            )
            ->get();



        foreach($devices as $device){


            $device->update([

                'status'=>'offline'

            ]);



            DeviceEvent::create([

                'device_id'=>$device->id,

                'event'=>'offline'

            ]);



        }



        return $devices->count();


    }


}