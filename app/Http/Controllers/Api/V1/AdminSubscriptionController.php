<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\AdminActivityLogger;


class AdminSubscriptionController extends Controller
{


    /**
     * List subscriptions
     */
    public function index(): JsonResponse
    {

        $subscriptions = Subscription::with([
            'plan',
            'user',
            'device'
        ])
        ->latest()
        ->get();



        return response()->json([

            'success'=>true,

            'message'=>'Subscriptions retrieved.',

            'data'=>[

                'subscriptions'=>$subscriptions

            ]

        ]);

    }







    /**
     * Cancel subscription
     */
    public function cancel(
        Request $request,
        int $id
    ): JsonResponse
    {


        $subscription = Subscription::find($id);



        if(!$subscription){

            return response()->json([

                'success'=>false,

                'message'=>'Subscription not found.'

            ],404);

        }






        $subscription->update([

            'status'=>'cancelled'

        ]);







        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */


        AdminActivityLogger::log(

            'SUBSCRIPTION_CANCELLED',

            'Cancelled subscription ID: '.$subscription->id,

            $request

        );







        return response()->json([

            'success'=>true,

            'message'=>'Subscription cancelled successfully.',

            'data'=>[

                'subscription'=>$subscription

            ]

        ]);

    }


}