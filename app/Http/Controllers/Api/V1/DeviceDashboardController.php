<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\DeviceEvent;
use App\Models\ProtectionSyncLog;
use App\Models\ProtectionViolationLog;
use App\Models\ProtectionNotification;

use Illuminate\Http\JsonResponse;



class DeviceDashboardController extends Controller
{


    public function dashboard(
        int $id
    ): JsonResponse
    {


        $user = auth('api')->user();



        /*
        |--------------------------------------------------------------------------
        | Verify Device
        |--------------------------------------------------------------------------
        */


        $device = $user->devices()
            ->where(
                'id',
                $id
            )
            ->first();



        if(!$device){

            return response()->json([

                'success'=>false,

                'message'=>'Device not found.'

            ],404);

        }



        /*
        |--------------------------------------------------------------------------
        | Latest Sync
        |--------------------------------------------------------------------------
        */


        $sync = ProtectionSyncLog::where(
                'device_id',
                $device->id
            )
            ->where(
                'apply_status',
                'applied'
            )
            ->latest('id')
            ->first();


        $syncIssue = ProtectionSyncLog::where(
                'device_id',
                $device->id
            )
            ->whereIn(
                'apply_status',
                [
                    'pending',
                    'failed'
                ]
            )
            ->latest('id')
            ->first();



        /*
        |--------------------------------------------------------------------------
        | Protection Setting
        |--------------------------------------------------------------------------
        */


        $setting = $device->protectionSetting;



        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */


        $blockedToday = ProtectionViolationLog::where(
                'device_id',
                $device->id
            )
            ->where(
                'action',
                'blocked'
            )
            ->whereDate(
                'created_at',
                today()
            )
            ->count();



        $totalBlocked = ProtectionViolationLog::where(
                'device_id',
                $device->id
            )
            ->where(
                'action',
                'blocked'
            )
            ->count();



        $unreadNotifications = ProtectionNotification::where(
                'device_id',
                $device->id
            )
            ->whereNull('read_at')
            ->count();



        /*
        |--------------------------------------------------------------------------
        | Latest Events
        |--------------------------------------------------------------------------
        */


        $events = DeviceEvent::where(
                'device_id',
                $device->id
            )
            ->latest('id')
            ->limit(5)
            ->get([
                'event',
                'created_at'
            ]);





        return response()->json([


            'success'=>true,


            'data'=>[


                'device'=>[

                    'id'=>$device->id,

                    'status'=>$device->status ?? null,

                    'last_seen_at'=>$device->last_seen_at ?? null,

                ],



                'protection'=>[

                    'status'=>$setting?->protection_status,

                    'dns_protection'=>(bool)$setting?->dns_protection,

                    'safe_search'=>(bool)$setting?->safe_search,

                    'betting_block'=>(bool)$setting?->betting_block,

                ],



                'sync'=>[

                    'status'=>$sync?->apply_status,

                    'version'=>$sync?->sync_version,

                    'synced_at'=>$sync?->synced_at,

                ],

                
                'sync_issue'=>[
                    'status'=>$syncIssue?->apply_status,
                    'version'=>$syncIssue?->sync_version,
                    'reason'=>$syncIssue?->failure_reason,
                ],



                'security'=>[

                    'blocked_today'=>$blockedToday,

                    'total_blocked'=>$totalBlocked,

                    'unread_notifications'=>$unreadNotifications,

                ],



                'latest_events'=>$events


            ]


        ]);



    }


}