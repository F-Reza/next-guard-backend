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
     * Confirm payment and activate subscription
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


                'user_id'=>$user->id,


                'subscription_id'=>$subscription->id,


                'gateway'=>
                    $data['gateway'] ?? 'manual',


                'transaction_id'=>
                    $data['transaction_id'],


                'amount'=>
                    $data['amount'],


                'currency'=>
                    $data['currency'] ?? 'USD',


                'status'=>'paid',


                'paid_at'=>now(),


            ]);







            /*
            |--------------------------------------------------------------------------
            | Activate Subscription
            |--------------------------------------------------------------------------
            */


            $subscription->update([


                'status'=>'active',


                'payment_reference'=>
                    $payment->transaction_id,


            ]);


            ProtectionActivationService::activate(
                $subscription
            );




            /*
            |--------------------------------------------------------------------------
            | Generate Invoice
            |--------------------------------------------------------------------------
            */


            $invoice = SubscriptionInvoice::create([


                'user_id'=>$user->id,


                'subscription_id'=>$subscription->id,


                'invoice_no'=>
                    self::invoiceNumber(),


                'amount'=>
                    $payment->amount,


                'currency'=>
                    $payment->currency,


                'status'=>'paid',


            ]);








            return [


                'payment'=>$payment,


                'invoice'=>$invoice,


                'subscription'=>$subscription,


            ];



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