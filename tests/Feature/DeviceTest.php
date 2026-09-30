<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class DeviceTest extends TestCase
{

    use RefreshDatabase;



    private function authUser()
    {

        $user = User::factory()->create();


        $token = auth('api')
            ->login($user);


        return [
            $user,
            $token
        ];

    }





    /**
     * Device enroll
     */
    public function test_user_can_enroll_device()
    {

        [$user,$token] = $this->authUser();



        $response = $this
            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )
            ->postJson(
                '/api/v1/devices/enroll',
                [

                    'device_uuid'=>'android-test-001',

                    'platform'=>'android',

                    'model'=>'Pixel Test',

                    'manufacturer'=>'Google',

                    'android_version'=>'14',

                    'app_version'=>'1.0.0',

                    'management_mode'=>'standard',

                ]
            );



        $response
            ->assertStatus(201)
            ->assertJson([
                'success'=>true
            ]);



        $this->assertDatabaseHas(
            'devices',
            [
                'user_id'=>$user->id
            ]
        );

    }





    /**
     * Device list
     */
    public function test_user_can_view_devices()
    {

        [$user,$token] = $this->authUser();



        Device::create([

            'user_id'=>$user->id,

            'device_uuid_hash'=>hash(
                'sha256',
                'device-001'
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
                '/api/v1/devices'
            );



        $response
            ->assertStatus(200)
            ->assertJson([
                'success'=>true
            ]);

    }





    /**
     * Device details
     */
    public function test_user_can_view_device_details()
    {

        [$user,$token] = $this->authUser();



        $device = Device::create([

            'user_id'=>$user->id,

            'device_uuid_hash'=>hash(
                'sha256',
                'device-002'
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
                '/api/v1/devices/'.$device->id
            );



        $response
            ->assertStatus(200)
            ->assertJson([
                'success'=>true
            ]);

    }





    /**
     * Heartbeat
     */
    public function test_device_heartbeat()
    {

        [$user,$token] = $this->authUser();



        $device = Device::create([

            'user_id'=>$user->id,

            'device_uuid_hash'=>hash(
                'sha256',
                'device-003'
            ),

            'platform'=>'android',

            'status'=>'offline',

        ]);




        $response = $this
            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )
            ->postJson(
                '/api/v1/devices/'.$device->id.'/heartbeat',
                [

                    'app_version'=>'1.0.1'

                ]
            );



        $response
            ->assertStatus(200)
            ->assertJson([
                'success'=>true
            ]);



        $this->assertDatabaseHas(
            'devices',
            [

                'id'=>$device->id,

                'status'=>'active'

            ]
        );

    }


}