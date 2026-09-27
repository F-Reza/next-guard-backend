<?php

namespace App\Services;


use App\Models\Device;
use App\Models\DeviceSession;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class DeviceEnrollmentService
{


    public static function enroll(
        User $user,
        array $data,
        ?string $ip = null,
        ?string $userAgent = null
    ): array
    {


        return DB::transaction(function() use(
            $user,
            $data,
            $ip,
            $userAgent
        ){


            $uuidHash = hash(
                'sha256',
                $data['device_uuid']
            );



            $isNew = false;



            $device = Device::where(
                    'user_id',
                    $user->id
                )
                ->where(
                    'device_uuid_hash',
                    $uuidHash
                )
                ->first();



            if(!$device){


                $isNew = true;


                $device = Device::create([


                    'user_id'=>$user->id,


                    'device_uuid_hash'=>$uuidHash,


                    'platform'=>$data['platform'] ?? 'android',


                    'model'=>$data['model'] ?? null,


                    'manufacturer'=>$data['manufacturer'] ?? null,


                    'android_version'=>$data['android_version'] ?? null,


                    'app_version'=>$data['app_version'] ?? null,


                    'management_mode'=>$data['management_mode'] ?? 'standard',


                    'status'=>'active',


                    'last_seen_at'=>now(),


                ]);



            }
            else{


                $device->update([


                    'platform'=>$data['platform'] ?? $device->platform,


                    'model'=>$data['model'] ?? $device->model,


                    'manufacturer'=>$data['manufacturer'] ?? $device->manufacturer,


                    'android_version'=>$data['android_version'] ?? $device->android_version,


                    'app_version'=>$data['app_version'] ?? $device->app_version,


                    'management_mode'=>$data['management_mode'] ?? $device->management_mode,


                    'status'=>'active',


                    'last_seen_at'=>now(),


                ]);


            }




            /*
            |--------------------------------------------------------------------------
            | Device Session
            |--------------------------------------------------------------------------
            */


            $refreshToken = Str::random(64);



            DeviceSession::create([


                'user_id'=>$user->id,


                'device_id'=>$device->id,


                'refresh_token_hash'=>hash(
                    'sha256',
                    $refreshToken
                ),


                'status'=>'active',


                'expires_at'=>now()->addDays(30),


                'last_used_at'=>now(),


                'ip_address'=>$ip,


                'user_agent'=>$userAgent,


            ]);





            return [


                'device'=>$device,


                'refresh_token'=>$refreshToken,


                'is_new'=>$isNew,


            ];



        });


    }


}