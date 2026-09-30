<?php

namespace App\Services\Payment;


class PaymentGatewayManager
{


    public static function driver(
        string $gateway
    )
    {


        return match($gateway){


            'stripe'
                =>
                app(StripeGateway::class),


            'sslcommerz'
                =>
                app(SSLCommerzGateway::class),



            default
                =>
                throw new \Exception(
                    'Gateway not supported.'
                )

        };


    }


}