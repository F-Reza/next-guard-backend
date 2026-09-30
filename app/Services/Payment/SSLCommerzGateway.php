<?php

namespace App\Services\Payment;


class SSLCommerzGateway implements GatewayInterface
{


    public function createPayment(
        array $data
    ): array
    {


        return [

            'gateway'=>'sslcommerz',

            'transaction_id'=>
                'SSL-'
                .str()->random(12),

            'amount'=>
                $data['amount'],

            'currency'=>
                $data['currency'] ?? 'BDT',

            'status'=>'pending'

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