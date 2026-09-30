<?php

namespace Tests\Feature;


use App\Models\Admin;
use App\Models\AdminNotification;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;



class AdminNotificationTest extends TestCase
{

    use RefreshDatabase;



    private function createAdmin()
    {


        $admin = Admin::create([

            'name'=>'Test Admin',

            'email'=>'admin@test.com',

            'password'=>bcrypt('password'),

            'role'=>'admin',

        ]);



        $token = auth('admin')
            ->login($admin);



        return [

            $admin,

            $token

        ];

    }






    /**
     * Admin can view notifications
     */
    public function test_admin_can_view_notifications()
    {


        [$admin,$token] =
            $this->createAdmin();




        AdminNotification::create([

            'admin_id'=>$admin->id,

            'type'=>'security',

            'title'=>'Test notification',

            'message'=>'Security alert',

            'is_read'=>false,

        ]);






        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->getJson(

                '/api/v1/admin/notifications'

            );






        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);

    }







    /**
     * Admin can view unread notifications
     */
    public function test_admin_can_view_unread_notifications()
    {


        [$admin,$token] =
            $this->createAdmin();




        AdminNotification::create([

            'admin_id'=>$admin->id,

            'type'=>'security',

            'title'=>'Unread',

            'message'=>'Need attention',

            'is_read'=>false,

        ]);





        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->getJson(

                '/api/v1/admin/notifications/unread'

            );





        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);

    }







    /**
     * Admin can mark notification read
     */
    public function test_admin_can_mark_notification_read()
    {


        [$admin,$token] =
            $this->createAdmin();




        $notification =
            AdminNotification::create([

                'admin_id'=>$admin->id,

                'type'=>'security',

                'title'=>'Read Test',

                'message'=>'Mark me',

                'is_read'=>false,

            ]);







        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->putJson(

                '/api/v1/admin/notifications/'
                .$notification->id
                .'/read'

            );





        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);





        $notification->refresh();


        $this->assertTrue(
            $notification->is_read
        );


    }







    /**
     * Admin can delete notification
     */
    public function test_admin_can_delete_notification()
    {


        [$admin,$token] =
            $this->createAdmin();




        $notification =
            AdminNotification::create([

                'admin_id'=>$admin->id,

                'type'=>'security',

                'title'=>'Delete Test',

                'message'=>'Delete me',

                'is_read'=>false,

            ]);






        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->deleteJson(

                '/api/v1/admin/notifications/'
                .$notification->id

            );





        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);





        $this->assertDatabaseMissing(

            'admin_notifications',

            [

                'id'=>$notification->id

            ]

        );


    }



}