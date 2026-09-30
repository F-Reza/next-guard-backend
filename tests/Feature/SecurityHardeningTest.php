<?php

namespace Tests\Feature;


use App\Models\User;
use App\Models\Device;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;



class SecurityHardeningTest extends TestCase
{

    use RefreshDatabase;



    private function createUserWithDevice()
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







    /**
     * User cannot access another user's device
     */
    public function test_user_cannot_access_other_user_device()
    {


        [
            $user,
            $device,
            $token

        ] = $this->createUserWithDevice();





        $otherUser = User::factory()->create();





        $otherDevice = Device::create([

            'user_id'=>$otherUser->id,

            'device_uuid_hash'=>hash(
                'sha256',
                uniqid()
            ),

            'platform'=>'android',

            'status'=>'active',

        ]);






        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->getJson(

                '/api/v1/devices/'.$otherDevice->id

            );





        $response

            ->assertStatus(404)

            ->assertJson([

                'success'=>false

            ]);



    }








    /**
     * Unauthenticated request rejected
     * Invalid JWT token rejected
     */
    public function test_invalid_token_rejected()
    {


        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer invalid-token'

            )

            ->getJson(

                '/api/v1/auth/me'

            );



        $response

            ->assertStatus(401);


    }





    /**
     * User cannot access admin dashboard
     */
    public function test_user_cannot_access_admin_dashboard()
    {


        $user = User::factory()->create();


        $token = auth('api')
            ->login($user);




        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->getJson(

                '/api/v1/admin/dashboard'

            );






        $response

            ->assertStatus(401);



    }



}