<?php

namespace Tests\Feature;


use App\Models\User;
use App\Models\Device;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;



class SubscriptionTest extends TestCase
{

    use RefreshDatabase;



    private function createUserDevice()
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



        return [
            $user,
            $device,
            $token
        ];

    }





    private function createPlan()
    {

        return SubscriptionPlan::create([

            'name'=>'Monthly',

            'price'=>10,

            'duration_days'=>30,

            'status'=>'active',

        ]);

    }







    /**
     * Plans list
     */
    public function test_user_can_view_subscription_plans()
    {


        [$user,$device,$token] =
            $this->createUserDevice();



        $this->createPlan();



        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->getJson(
                '/api/v1/subscription-plans'
            );



        $response

            ->assertStatus(200)

            ->assertJson([
                'success'=>true
            ]);

    }







    /**
     * Activate subscription
     */
    public function test_user_can_activate_subscription()
    {


        [$user,$device,$token] =
            $this->createUserDevice();



        $plan = $this->createPlan();



        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->postJson(
                '/api/v1/subscriptions/activate',
                [

                    'plan_id'=>$plan->id,

                    'device_id'=>$device->id

                ]
            );



        $response
            ->assertStatus(201)
            ->assertJson([
                'success'=>true
            ]);



        $this->assertDatabaseHas(

            'subscriptions',

            [

                'user_id'=>$user->id,

                'device_id'=>$device->id,

                'status'=>'active'

            ]

        );

    }







    /**
     * Current subscription
     */
    public function test_user_can_view_current_subscription()
    {


        [$user,$device,$token] =
            $this->createUserDevice();



        $plan = $this->createPlan();



        Subscription::create([

            'user_id'=>$user->id,

            'device_id'=>$device->id,

            'subscription_plan_id'=>$plan->id,

            'status'=>'active',

            'started_at'=>now(),

            'expires_at'=>now()->addMonth(),

        ]);




        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->getJson(
                '/api/v1/subscriptions/current'
            );



        $response

            ->assertStatus(200)

            ->assertJson([
                'success'=>true
            ]);

    }







    /**
     * Expired subscription
     */
    public function test_expired_subscription_is_inactive()
    {


        [$user,$device,$token] =
            $this->createUserDevice();



        $plan = $this->createPlan();



        Subscription::create([

            'user_id'=>$user->id,

            'device_id'=>$device->id,

            'subscription_plan_id'=>$plan->id,

            'status'=>'active',

            'started_at'=>now()->subMonth(),

            'expires_at'=>now()->subDay(),

        ]);



        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->getJson(
                '/api/v1/subscription/status'
            );



        $response

            ->assertStatus(200)

            ->assertJson([
                'success'=>true
            ]);

    }



}