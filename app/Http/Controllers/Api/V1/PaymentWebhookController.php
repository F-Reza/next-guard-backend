<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\PaymentWebhook;
use App\Models\Payment;

use App\Services\Payment\PaymentGatewayManager;
use App\Services\PaymentService;
use App\Services\Payment\WebhookSignatureService;

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


        /*
        |--------------------------------------------------------------------------
        | Validate Payload
        |--------------------------------------------------------------------------
        */


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
        | Verify Webhook Signature
        |--------------------------------------------------------------------------
        */


        $signature =
            $request->header(
                'X-Webhook-Signature'
            );



        if(!$signature){


            return response()->json([

                'success'=>false,

                'message'=>'Webhook signature missing.'

            ],401);


        }





        $isValid =
            WebhookSignatureService::verify(


                $request->getContent(),


                $signature,


                config(
                    'services.payment.webhook_secret'
                )


            );





        if(!$isValid){


            return response()->json([

                'success'=>false,

                'message'=>'Invalid webhook signature.'

            ],401);


        }








        /*
        |--------------------------------------------------------------------------
        | Duplicate Event Protection
        |--------------------------------------------------------------------------
        */


        $exists =
            PaymentWebhook::where(

                'event_id',

                $request->event_id

            )
            ->exists();




        if($exists){


            return response()->json([

                'success'=>false,

                'message'=>'Webhook already processed.'

            ],409);


        }








        try {



            /*
            |--------------------------------------------------------------------------
            | Gateway Verification
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
            | Create Webhook Record
            |--------------------------------------------------------------------------
            */


            $webhook =
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
                        'processing',


                ]);









            /*
            |--------------------------------------------------------------------------
            | Complete Payment
            |--------------------------------------------------------------------------
            */


            PaymentService::completePayment(
                $payment
            );









            /*
            |--------------------------------------------------------------------------
            | Mark Webhook Completed
            |--------------------------------------------------------------------------
            */


            $webhook->update([


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
        catch(\Throwable $e){






            /*
            |--------------------------------------------------------------------------
            | Mark Failed Webhook
            |--------------------------------------------------------------------------
            */


            if(isset($webhook)){


                $webhook->update([


                    'status'=>
                        'failed',


                    'processed_at'=>
                        now(),


                ]);


            }






            return response()->json([


                'success'=>false,


                'message'=>
                    $e->getMessage()



            ],422);




        }



    }


}