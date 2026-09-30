<?php

namespace App\Services\Payment;


interface GatewayInterface
{


    public function createPayment(
        array $data
    ): array;



    public function verifyPayment(
        string $transactionId
    ): array;



}
