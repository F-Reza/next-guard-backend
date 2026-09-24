<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;


class AdminSubscriptionController extends Controller
{


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





    public function cancel(int $id): JsonResponse
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



        return response()->json([

            'success'=>true,

            'message'=>'Subscription cancelled successfully.',

            'data'=>[

                'subscription'=>$subscription

            ]

        ]);

    }


}