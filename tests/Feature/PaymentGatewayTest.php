<?php

namespace Tests\Feature;


use App\Models\User;
use App\Models\Device;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;



class PaymentGatewayTest extends TestCase
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

            'started_at'=>now(),

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
     * User can create stripe payment
     */
    public function test_user_can_create_stripe_payment()
    {


        [
            $user,
            $device,
            $subscription,
            $token

        ] = $this->createSubscription();





        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->postJson(

                '/api/v1/payments/create',

                [

                    'subscription_id'=>
                        $subscription->id,


                    'gateway'=>
                        'stripe'

                ]

            );






        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true,

                'data'=>[

                    'gateway'=>'stripe',

                    'status'=>'pending'

                ]

            ]);



    }







    /**
     * User can create SSLCommerz payment
     */
    public function test_user_can_create_sslcommerz_payment()
    {


        [
            $user,
            $device,
            $subscription,
            $token

        ] = $this->createSubscription();





        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->postJson(

                '/api/v1/payments/create',

                [

                    'subscription_id'=>
                        $subscription->id,


                    'gateway'=>
                        'sslcommerz'

                ]

            );






        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true,

                'data'=>[

                    'gateway'=>'sslcommerz',

                    'status'=>'pending'

                ]

            ]);


    }








    /**
     * Unsupported gateway rejected
     */
    public function test_invalid_gateway_rejected()
    {


        [
            $user,
            $device,
            $subscription,
            $token

        ] = $this->createSubscription();






        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->postJson(

                '/api/v1/payments/create',

                [

                    'subscription_id'=>
                        $subscription->id,


                    'gateway'=>
                        'unknown'

                ]

            );






        $response

            ->assertStatus(422)

            ->assertJson([

                'success'=>false

            ]);



    }



}