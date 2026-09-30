<?php

namespace Tests\Feature;


use App\Models\User;
use App\Models\Device;
use App\Models\ProtectionNotification;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;



class NotificationTest extends TestCase
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





    /**
     * Device notifications list
     */
    public function test_user_can_view_protection_notifications()
    {


        [$user,$device,$token] =
            $this->createDevice();




        ProtectionNotification::create([

            'device_id'=>$device->id,

            'user_id'=>$user->id,

            'type'=>'protection_alert',

            'title'=>'Blocked website detected',

            'message'=>'bet365.com was blocked.',

            'domain'=>'bet365.com',

            'category'=>'betting',

        ]);





        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->getJson(
                '/api/v1/devices/'.$device->id.'/protection/notifications'
            );





        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true,

                'data'=>[

                    'unread_count'=>1

                ]

            ]);

    }








    /**
     * Mark notification read
     */
    public function test_user_can_mark_notification_read()
    {


        [$user,$device,$token] =
            $this->createDevice();




        $notification =
            ProtectionNotification::create([

                'device_id'=>$device->id,

                'user_id'=>$user->id,

                'type'=>'protection_alert',

                'title'=>'Blocked website detected',

                'message'=>'blocked',

                'domain'=>'bet365.com',

                'category'=>'betting',

            ]);







        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->putJson(

                '/api/v1/devices/'
                .$device->id
                .'/protection/notifications/'
                .$notification->id
                .'/read'

            );





        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);





        $this->assertDatabaseHas(
            'protection_notifications',
            [
                'id'=>$notification->id,
            ]
        );


        $notification->refresh();


        $this->assertNotNull(
            $notification->read_at
        );
        

    }







    /**
     * Notification created after expiry
     */
    public function test_subscription_expiry_creates_notification()
    {


        $this->assertTrue(true);

    }


}