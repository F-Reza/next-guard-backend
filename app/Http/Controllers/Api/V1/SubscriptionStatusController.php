<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\DeviceProtectionSetting;

use Illuminate\Http\JsonResponse;



class SubscriptionStatusController extends Controller
{


    /**
     * Subscription dashboard status
     */
    public function status(): JsonResponse
    {


        $user = auth('api')->user();



        $subscription = $user->subscriptions()
            ->with('plan')
            ->latest()
            ->first();


        if(!$subscription){

            $subscription = $user->subscriptions()
                ->with('plan')
                ->latest()
                ->first();

        }


        if(!$subscription){

            return response()->json([

                'success'=>true,

                'message'=>'No subscription found.',

                'data'=>[

                    'subscription'=>null,

                    'protection'=>null

                ]

            ]);

        }





        $isActive =
            $subscription->status === 'active'
            &&
            $subscription->expires_at
            &&
            $subscription->expires_at->isFuture();







        $devices = $user->devices()
            ->count();






        $protection = null;



        if($subscription->device_id){


            $setting =
                DeviceProtectionSetting::where(
                    'device_id',
                    $subscription->device_id
                )
                ->first();



            if($setting){

                $protection=[

                    'device_id'=>$setting->device_id,

                    'status'=>$setting->protection_status,

                    'dns_protection'=>
                        (bool)$setting->dns_protection,

                    'safe_search'=>
                        (bool)$setting->safe_search,

                    'last_sync_at'=>
                        $setting->last_sync_at,

                ];

            }


        }







        return response()->json([


            'success'=>true,


            'message'=>'Subscription status retrieved.',



            'data'=>[


                'subscription'=>[

                    'id'=>$subscription->id,

                    'active'=>$isActive,

                    'status'=>$subscription->status,

                    'auto_renew'=>$subscription->auto_renew,

                    'source'=>$subscription->source,

                    'plan'=>$subscription->plan,

                    'starts_at'=>$subscription->starts_at,

                    'expires_at'=>$subscription->expires_at,

                    'days_remaining'=>
                        $isActive
                        ?
                        (int) now()->diffInDays(
                            $subscription->expires_at
                        )
                        :
                        0,

                ],



                'devices_count'=>$devices,



                'protection'=>$protection,


            ]


        ]);



    }



}