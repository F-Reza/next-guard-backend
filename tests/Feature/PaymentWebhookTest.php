<?php

namespace Tests\Feature;


use App\Models\User;
use App\Models\Device;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\Payment;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;



class PaymentWebhookTest extends TestCase
{

    use RefreshDatabase;



    private function createPayment()
    {


        $user = User::factory()->create();


        $token = auth('api')
            ->login($user);



        $device = Device::create([

            'user_id'=>$user->id,

            'device_uuid_hash'=>hash(
                'sha256',
                uniqid()
            ),

            'platform'=>'android',

            'status'=>'active',

        ]);




        $plan = SubscriptionPlan::create([

            'name'=>'Monthly',

            'price'=>10,

            'duration_days'=>30,

            'status'=>'active',

        ]);




        $subscription = Subscription::create([

            'user_id'=>$user->id,

            'device_id'=>$device->id,

            'subscription_plan_id'=>$plan->id,

            'status'=>'pending',

            'starts_at'=>now(),

            'expires_at'=>now()->addDays(30),

        ]);




        $payment = Payment::create([

            'user_id'=>$user->id,

            'subscription_id'=>$subscription->id,

            'gateway'=>'stripe',

            'transaction_id'=>'STRIPE-TEST123',

            'amount'=>10,

            'currency'=>'USD',

            'status'=>'pending',

        ]);




        return [

            $user,

            $subscription,

            $payment,

            $token

        ];

    }







    /**
     * Valid webhook processed
     */
    public function test_valid_webhook_processed()
    {


        [
            $user,
            $subscription,
            $payment,
            $token

        ] = $this->createPayment();





        $response = $this->postJson(

            '/api/v1/payments/webhook',

            [

                'gateway'=>'stripe',

                'event_id'=>'evt_test_001',

                'transaction_id'=>
                    $payment->transaction_id

            ]

        );





        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);





        $this->assertDatabaseHas(

            'payment_webhooks',

            [

                'event_id'=>'evt_test_001',

                'status'=>'processed'

            ]

        );



    }







    /**
     * Duplicate webhook rejected
     */
    public function test_duplicate_webhook_rejected()
    {


        [
            $user,
            $subscription,
            $payment,
            $token

        ] = $this->createPayment();




        $payload=[

            'gateway'=>'stripe',

            'event_id'=>'evt_duplicate',

            'transaction_id'=>
                $payment->transaction_id

        ];





        $this->postJson(

            '/api/v1/payments/webhook',

            $payload

        );





        $response = $this->postJson(

            '/api/v1/payments/webhook',

            $payload

        );





        $response

            ->assertStatus(409)

            ->assertJson([

                'success'=>false

            ]);



    }







    /**
     * Invalid gateway rejected
     */
    public function test_invalid_gateway_rejected()
    {


        [
            $user,
            $subscription,
            $payment,
            $token

        ] = $this->createPayment();





        $response = $this->postJson(

            '/api/v1/payments/webhook',

            [

                'gateway'=>'unknown',

                'event_id'=>'evt_invalid',

                'transaction_id'=>
                    $payment->transaction_id

            ]

        );





        $response

            ->assertStatus(422)

            ->assertJson([

                'success'=>false

            ]);



    }



}