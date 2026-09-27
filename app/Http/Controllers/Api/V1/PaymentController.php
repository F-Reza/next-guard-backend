<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\Payment;

use App\Models\Subscription;

use App\Models\SubscriptionInvoice;

use App\Services\PaymentService;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;



class PaymentController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Confirm Payment
    |--------------------------------------------------------------------------
    */


    public function confirm(
        Request $request
    ): JsonResponse
    {


        $request->validate([


            'subscription_id'=>[
                'required',
                'exists:subscriptions,id'
            ],


            'transaction_id'=>[
                'required',
                'string',
                'unique:payments,transaction_id'
            ],


            'amount'=>[
                'required',
                'numeric'
            ],


            'gateway'=>[
                'nullable',
                'string'
            ],


            'currency'=>[
                'nullable',
                'string',
                'max:10'
            ]


        ]);




        $user = auth('api')->user();





        $subscription = $user
            ->subscriptions()
            ->where(
                'id',
                $request->subscription_id
            )
            ->first();





        if(!$subscription){


            return response()->json([


                'success'=>false,

                'message'=>'Subscription not found.'


            ],404);


        }







        $result = PaymentService::confirmPayment(

            $user,

            $subscription,

            $request->all()

        );








        return response()->json([


            'success'=>true,


            'message'=>'Payment confirmed successfully.',



            'data'=>[


                'payment'=>$result['payment'],


                'invoice'=>$result['invoice'],


                'subscription'=>$result['subscription']


            ]



        ]);



    }








    /*
    |--------------------------------------------------------------------------
    | Payment History
    |--------------------------------------------------------------------------
    */


    public function history(): JsonResponse
    {


        $payments = auth('api')
            ->user()
            ->payments()
            ->latest()
            ->get();




        return response()->json([


            'success'=>true,


            'message'=>'Payment history retrieved.',



            'data'=>[

                'payments'=>$payments

            ]


        ]);



    }








    /*
    |--------------------------------------------------------------------------
    | Invoice List
    |--------------------------------------------------------------------------
    */


    public function invoices(): JsonResponse
    {


        $invoices = auth('api')
            ->user()
            ->invoices()
            ->latest()
            ->get();




        return response()->json([


            'success'=>true,


            'message'=>'Invoices retrieved.',



            'data'=>[

                'invoices'=>$invoices

            ]


        ]);



    }



}