<?php

namespace Tests\Feature;


use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;



class PaymentWebhookSecurityTest extends TestCase
{

    use RefreshDatabase;



    public function test_webhook_without_signature_rejected()
    {


        $response = $this->postJson(
            '/api/v1/payments/webhook',
            [

                'gateway'=>'stripe',

                'event_id'=>'event-1',

                'transaction_id'=>'tx-1',

            ]
        );


        $response->assertStatus(401);


    }





    public function test_invalid_signature_rejected()
    {


        Config::set(
            'services.payment.webhook_secret',
            'secret'
        );



        $response = $this

            ->withHeader(
                'X-Webhook-Signature',
                'wrong-signature'
            )

            ->postJson(
                '/api/v1/payments/webhook',
                [

                    'gateway'=>'stripe',

                    'event_id'=>'event-2',

                    'transaction_id'=>'tx-2',

                ]
            );



        $response->assertStatus(401);


    }


}