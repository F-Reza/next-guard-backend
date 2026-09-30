<?php

namespace Tests\Feature;


use App\Models\User;
use App\Models\Device;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Payment;
use App\Models\SubscriptionInvoice;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;



class PaymentTest extends TestCase
{

    use RefreshDatabase;



    private function createSubscription()
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

            'expires_at'=>now()->addMonth(),

        ]);




        return [

            $user,

            $device,

            $subscription,

            $token

        ];

    }






    /**
     * Payment confirm
     */
    public function test_user_can_confirm_payment()
    {


        [$user,$device,$subscription,$token]
            =
            $this->createSubscription();




        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->postJson(
                '/api/v1/payments/confirm',
                [

                    'subscription_id'=>$subscription->id,

                    'transaction_id'=>'TXN-12345',

                    'amount'=>10,

                    'gateway'=>'manual',

                    'currency'=>'USD'

                ]
            );




        $response

            ->assertStatus(200)

            ->assertJson([
                'success'=>true
            ]);





        $this->assertDatabaseHas(
            'payments',
            [

                'transaction_id'=>'TXN-12345',

                'status'=>'paid'

            ]
        );




        $this->assertDatabaseHas(
            'subscription_invoices',
            [

                'subscription_id'=>$subscription->id,

                'status'=>'paid'

            ]
        );



        $this->assertDatabaseHas(
            'subscriptions',
            [

                'id'=>$subscription->id,

                'status'=>'active'

            ]
        );


    }






    /**
     * Payment history
     */
    public function test_user_can_view_payment_history()
    {


        [$user,$device,$subscription,$token]
            =
            $this->createSubscription();



        Payment::create([

            'user_id'=>$user->id,

            'subscription_id'=>$subscription->id,

            'gateway'=>'manual',

            'transaction_id'=>'TXN-HISTORY',

            'amount'=>10,

            'currency'=>'USD',

            'status'=>'paid',

            'paid_at'=>now(),

        ]);




        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->getJson(
                '/api/v1/payments'
            );




        $response

            ->assertStatus(200)

            ->assertJson([
                'success'=>true
            ]);

    }







    /**
     * Invoice list
     */
    public function test_user_can_view_invoices()
    {


        [$user,$device,$subscription,$token]
            =
            $this->createSubscription();



        SubscriptionInvoice::create([

            'user_id'=>$user->id,

            'subscription_id'=>$subscription->id,

            'invoice_no'=>'INV-TEST-001',

            'amount'=>10,

            'currency'=>'USD',

            'status'=>'paid',

        ]);




        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->getJson(
                '/api/v1/invoices'
            );




        $response

            ->assertStatus(200)

            ->assertJson([
                'success'=>true
            ]);

    }







    /**
     * Duplicate transaction rejected
     */
    public function test_duplicate_transaction_rejected()
    {


        [$user,$device,$subscription,$token]
            =
            $this->createSubscription();




        Payment::create([

            'user_id'=>$user->id,

            'subscription_id'=>$subscription->id,

            'gateway'=>'manual',

            'transaction_id'=>'TXN-DUP',

            'amount'=>10,

            'currency'=>'USD',

            'status'=>'paid',

        ]);





        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->postJson(
                '/api/v1/payments/confirm',
                [

                    'subscription_id'=>$subscription->id,

                    'transaction_id'=>'TXN-DUP',

                    'amount'=>10

                ]
            );




        $response

            ->assertStatus(422);

    }



}