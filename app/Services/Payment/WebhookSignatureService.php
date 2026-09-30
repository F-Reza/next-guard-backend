<?php

namespace App\Services\Payment;


class WebhookSignatureService
{


    /**
     * Verify webhook signature
     */
    public static function verify(
        string $payload,
        string $signature,
        string $secret
    ): bool
    {


        $expected =
            hash_hmac(
                'sha256',
                $payload,
                $secret
            );


        return hash_equals(
            $expected,
            $signature
        );


    }



}