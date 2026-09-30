<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\PaymentWebhook;
use App\Models\Payment;

use App\Services\Payment\PaymentGatewayManager;
use App\Services\PaymentService;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;



class PaymentWebhookController extends Controller
{


    /**
     * Handle payment gateway webhook
     */
    public function handle(
        Request $request
    ): JsonResponse
    {


        $request->validate([


            'gateway'=>[
                'required',
                'string'
            ],


            'event_id'=>[
                'required',
                'string'
            ],


            'transaction_id'=>[
                'required',
                'string'
            ],


        ]);






        /*
        |--------------------------------------------------------------------------
        | Duplicate webhook protection
        |--------------------------------------------------------------------------
        */


        $exists = PaymentWebhook::where(
            'event_id',
            $request->event_id
        )->exists();



        if($exists){


            return response()->json([

                'success'=>false,

                'message'=>'Webhook already processed.'

            ],409);


        }







        try {



            /*
            |--------------------------------------------------------------------------
            | Gateway verification
            |--------------------------------------------------------------------------
            */


            $gateway =
                PaymentGatewayManager::driver(
                    $request->gateway
                );



            $verification =
                $gateway->verifyPayment(
                    $request->transaction_id
                );





            if(
                !($verification['verified'] ?? false)
            ){


                return response()->json([

                    'success'=>false,

                    'message'=>'Payment verification failed.'

                ],422);


            }







            /*
            |--------------------------------------------------------------------------
            | Find Payment
            |--------------------------------------------------------------------------
            */


            $payment =
                Payment::where(

                    'transaction_id',

                    $request->transaction_id

                )
                ->first();



            if(!$payment){


                return response()->json([

                    'success'=>false,

                    'message'=>'Payment not found.'

                ],404);


            }








            /*
            |--------------------------------------------------------------------------
            | Complete Payment Business Flow
            |--------------------------------------------------------------------------
            */


            PaymentService::completePayment(
                $payment
            );








            /*
            |--------------------------------------------------------------------------
            | Store Webhook Event
            |--------------------------------------------------------------------------
            */


            PaymentWebhook::create([


                'gateway'=>
                    $request->gateway,


                'event_id'=>
                    $request->event_id,


                'transaction_id'=>
                    $request->transaction_id,


                'payload'=>
                    $request->all(),


                'status'=>
                    'processed',


                'processed_at'=>
                    now(),


            ]);







            return response()->json([


                'success'=>true,


                'message'=>
                    'Webhook processed successfully.'


            ]);




        }
        catch(\Exception $e){



            return response()->json([


                'success'=>false,


                'message'=>$e->getMessage()


            ],422);



        }



    }


}