<?php

namespace Tests\Feature;


use App\Models\User;
use App\Models\Device;
use App\Models\SubscriptionPlan;
use App\Models\LicenseCode;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;



class LicenseTest extends TestCase
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
     * User can redeem license
     */
    public function test_user_can_redeem_license()
    {


        [$user,$device,$token] =
            $this->createUserDevice();



        $plan = $this->createPlan();



        $license = LicenseCode::create([

            'code'=>'NG-MONTHLY-TEST123',

            'subscription_plan_id'=>$plan->id,

            'duration_days'=>30,

            'status'=>'active',

        ]);




        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->postJson(

                '/api/v1/license-codes/redeem',

                [

                    'code'=>$license->code,

                    'device_id'=>$device->id

                ]

            );




        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);




        $this->assertDatabaseHas(

            'license_codes',

            [

                'id'=>$license->id,

                'status'=>'used',

                'used_by'=>$user->id

            ]

        );




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
     * Invalid license rejected
     */
    public function test_invalid_license_rejected()
    {


        [$user,$device,$token] =
            $this->createUserDevice();



        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->postJson(

                '/api/v1/license-codes/redeem',

                [

                    'code'=>'INVALID-CODE',

                    'device_id'=>$device->id

                ]

            );



        $response

            ->assertStatus(404)

            ->assertJson([

                'success'=>false

            ]);

    }






    /**
     * Used license cannot redeem again
     */
    public function test_used_license_rejected()
    {


        [$user,$device,$token] =
            $this->createUserDevice();



        $plan = $this->createPlan();



        $license = LicenseCode::create([

            'code'=>'NG-USED-123',

            'subscription_plan_id'=>$plan->id,

            'duration_days'=>30,

            'status'=>'used',

            'used_by'=>$user->id,

            'used_device_id'=>$device->id,

            'used_at'=>now(),

        ]);





        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->postJson(

                '/api/v1/license-codes/redeem',

                [

                    'code'=>$license->code,

                    'device_id'=>$device->id

                ]

            );




        $response

            ->assertStatus(409)

            ->assertJson([

                'success'=>false,

                'message'=>'License code already used or inactive.'

            ]);

    }



}