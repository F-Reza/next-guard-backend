<?php

namespace App\Services\Payment;


class StripeGateway implements GatewayInterface
{


    public function createPayment(
        array $data
    ): array
    {


        return [

            'gateway'=>'stripe',

            'transaction_id'=>
                'STRIPE-'
                .str()->random(12),


            'amount'=>
                $data['amount'],


            'currency'=>
                $data['currency'] ?? 'USD',


            'status'=>
                'pending'


        ];


    }




    public function verifyPayment(
        string $transactionId
    ): array
    {


        return [

            'verified'=>true,

            'transaction_id'=>$transactionId

        ];


    }


}