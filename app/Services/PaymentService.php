<?php

namespace App\Services;


use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionInvoice;

use App\Services\ProtectionActivationService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class PaymentService
{



    /**
     * Complete Payment
     *
     * Used by:
     * - Manual payment confirmation
     * - Gateway webhook
     */
    public static function completePayment(
        Payment $payment
    ): array
    {


        return DB::transaction(function() use($payment){



            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate completion
            |--------------------------------------------------------------------------
            */


            if($payment->status === 'paid'){


                return [

                    'payment'=>$payment,

                    'invoice'=>
                        SubscriptionInvoice::where(
                            'subscription_id',
                            $payment->subscription_id
                        )
                        ->first(),

                    'subscription'=>
                        $payment->subscription

                ];


            }





            /*
            |--------------------------------------------------------------------------
            | Mark payment paid
            |--------------------------------------------------------------------------
            */


            $payment->update([

                'status'=>'paid',

                'paid_at'=>now()

            ]);





            /*
            |--------------------------------------------------------------------------
            | Activate Subscription
            |--------------------------------------------------------------------------
            */


            $subscription =
                $payment
                ->load('subscription')
                ->subscription;



            $subscription->update([

                'status'=>'active',

                'payment_reference'=>
                    $payment->transaction_id

            ]);





            /*
            |--------------------------------------------------------------------------
            | Enable Protection
            |--------------------------------------------------------------------------
            */


            ProtectionActivationService::activate(
                $subscription
            );







            /*
            |--------------------------------------------------------------------------
            | Create Invoice
            |--------------------------------------------------------------------------
            */


            $invoice =
                SubscriptionInvoice::firstOrCreate(


                    [

                        'subscription_id'=>
                            $subscription->id,

                        'status'=>'paid'

                    ],


                    [

                        'user_id'=>
                            $payment->user_id,


                        'invoice_no'=>
                            self::invoiceNumber(),


                        'amount'=>
                            $payment->amount,


                        'currency'=>
                            $payment->currency,


                    ]

                );






            return [


                'payment'=>
                    $payment->fresh(),


                'invoice'=>
                    $invoice,


                'subscription'=>
                    $subscription->fresh(),


            ];



        });


    }








    /**
     * Confirm manual payment
     */
    public static function confirmPayment(
        User $user,
        Subscription $subscription,
        array $data
    ): array
    {


        return DB::transaction(function() use(

            $user,

            $subscription,

            $data

        ){



            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            */


            $payment = Payment::create([


                'user_id'=>
                    $user->id,


                'subscription_id'=>
                    $subscription->id,


                'gateway'=>
                    $data['gateway'] ?? 'manual',


                'transaction_id'=>
                    $data['transaction_id'],


                'amount'=>
                    $data['amount'],


                'currency'=>
                    $data['currency'] ?? 'USD',


                'status'=>
                    'pending',


            ]);







            /*
            |--------------------------------------------------------------------------
            | Complete Payment Flow
            |--------------------------------------------------------------------------
            */


            return self::completePayment(
                $payment
            );



        });


    }







    /**
     * Generate invoice number
     */
    private static function invoiceNumber(): string
    {


        return 'INV-'
            .
            now()->format('Ymd')
            .
            '-'
            .
            strtoupper(
                Str::random(6)
            );


    }



}