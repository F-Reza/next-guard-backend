<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\User;
use App\Models\Device;

use Illuminate\Foundation\Testing\RefreshDatabase;



class RateLimitTest extends TestCase
{

    use RefreshDatabase;



    private function createDevice()
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





    public function test_login_rate_limit()
    {


        for($i=0;$i<6;$i++){


            $response = $this->postJson(

                '/api/v1/auth/login',

                [

                    'email'=>'wrong@test.com',

                    'password'=>'wrong'

                ]

            );


        }


        $response->assertStatus(429);


    }





    public function test_payment_rate_limit()
    {

        [$user,$device,$token] =
            $this->createDevice();



        for($i=0;$i<11;$i++){


            $response = $this

                ->withHeader(

                    'Authorization',

                    'Bearer '.$token

                )

                ->postJson(

                    '/api/v1/payments/confirm',

                    [

                        'subscription_id'=>999,

                        'transaction_id'=>'TX-'.$i,

                        'amount'=>10

                    ]

                );


        }


        $response->assertStatus(429);


    }





    public function test_license_rate_limit()
    {


        [$user,$device,$token] =
            $this->createDevice();



        for($i=0;$i<121;$i++){


            $response = $this

                ->withHeader(

                    'Authorization',

                    'Bearer '.$token

                )

                ->postJson(

                    '/api/v1/licenses/redeem',

                    [

                        'code'=>'INVALID',

                        'device_id'=>$device->id

                    ]

                );


        }



        $response->assertStatus(429);


    }




}