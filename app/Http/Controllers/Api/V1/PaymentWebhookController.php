<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\PaymentWebhook;
use App\Models\Payment;

use App\Services\Payment\PaymentGatewayManager;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;



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







            DB::transaction(function() use($request){



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




                Payment::where(

                    'transaction_id',

                    $request->transaction_id

                )
                ->update([

                    'status'=>'paid',

                    'paid_at'=>now(),

                ]);



            });





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