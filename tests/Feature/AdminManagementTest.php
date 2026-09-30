<?php

namespace Tests\Feature;


use App\Models\Admin;
use App\Models\Permission;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;



class AdminManagementTest extends TestCase
{

    use RefreshDatabase;



    private function createAdmin()
    {


        $permission = Permission::firstOrCreate([

            'name'=>'manage_admins'

        ]);



        $admin = Admin::create([

            'name'=>'Super Admin',

            'email'=>'super@test.com',

            'password'=>'password',

            'role'=>'super_admin',

            'status'=>'active',

        ]);



        $admin->permissions()->attach(
            $permission->id
        );



        $token = auth('admin')
            ->login($admin);



        return [

            $admin,

            $token

        ];

    }







    /**
     * Admin list
     */
    public function test_admin_can_view_admins()
    {


        [$admin,$token] =
            $this->createAdmin();



        Admin::create([

            'name'=>'Second Admin',

            'email'=>'admin2@test.com',

            'password'=>'password',

            'role'=>'admin',

            'status'=>'active',

        ]);



        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->getJson(
                '/api/v1/admin/admins'
            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);

    }







    /**
     * Create admin
     */
    public function test_super_admin_can_create_admin()
    {


        [$admin,$token] =
            $this->createAdmin();



        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->postJson(

                '/api/v1/admin/admins',

                [

                    'name'=>'New Admin',

                    'email'=>'newadmin@test.com',

                    'password'=>'password',

                    'role'=>'admin'

                ]

            );



        $response

            ->assertStatus(201)

            ->assertJson([

                'success'=>true

            ]);



        $this->assertDatabaseHas(

            'admins',

            [

                'email'=>'newadmin@test.com'

            ]

        );

    }







    /**
     * Show admin
     */
    public function test_admin_can_view_single_admin()
    {


        [$admin,$token] =
            $this->createAdmin();



        $target = Admin::create([

            'name'=>'Target Admin',

            'email'=>'target@test.com',

            'password'=>'password',

            'role'=>'admin',

            'status'=>'active',

        ]);



        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->getJson(

                '/api/v1/admin/admins/'.$target->id

            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);

    }







    /**
     * Update admin
     */
    public function test_super_admin_can_update_admin()
    {


        [$admin,$token] =
            $this->createAdmin();



        $target = Admin::create([

            'name'=>'Old Admin',

            'email'=>'old@test.com',

            'password'=>'password',

            'role'=>'admin',

            'status'=>'active'

        ]);



        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->putJson(

                '/api/v1/admin/admins/'.$target->id,

                [

                    'name'=>'Updated Admin',

                    'email'=>'updated@example.com',

                    'role'=>'admin',

                ]

            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);



        $this->assertDatabaseHas(

            'admins',

            [

                'id'=>$target->id,

                'name'=>'Updated Admin'

            ]

        );

    }









    /**
     * Reset password
     */
    public function test_super_admin_can_reset_admin_password()
    {


        [$admin,$token] =
            $this->createAdmin();



        $target = Admin::create([

            'name'=>'Reset Admin',

            'email'=>'reset@test.com',

            'password'=>'password',

            'role'=>'admin',

            'status'=>'active'

        ]);



        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->postJson(

                '/api/v1/admin/admins/'.$target->id.'/reset-password',

                [

                    'password'=>'newpassword123'

                ]

            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);

    }









    /**
     * Delete admin
     */
    public function test_super_admin_can_delete_admin()
    {


        [$admin,$token] =
            $this->createAdmin();



        $target = Admin::create([

            'name'=>'Delete Admin',

            'email'=>'delete@test.com',

            'password'=>'password',

            'role'=>'admin',

            'status'=>'active'

        ]);



        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->deleteJson(

                '/api/v1/admin/admins/'.$target->id

            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);



        $this->assertSoftDeleted(

            'admins',

            [

                'id'=>$target->id

            ]

        );

    }


    

    /**
     * Super admin can restore deleted admin
     */
    public function test_super_admin_can_restore_admin()
    {

        [$admin,$token] =
            $this->createAdmin();



        $target = Admin::create([

            'name'=>'Deleted Admin',

            'email'=>'restore@test.com',

            'password'=>'password',

            'role'=>'admin',

            'status'=>'active',

        ]);



        $target->delete();



        $this->assertSoftDeleted(

            'admins',

            [

                'id'=>$target->id

            ]

        );



        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->postJson(

                '/api/v1/admin/admins/'.$target->id.'/restore'

            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);



        $this->assertDatabaseHas(

            'admins',

            [

                'id'=>$target->id,

                'deleted_at'=>null

            ]

        );

    }





    /**
     * Super admin can unlock admin
     */
    public function test_super_admin_can_unlock_admin()
    {

        [$admin,$token] =
            $this->createAdmin();



        $target = Admin::create([

            'name'=>'Locked Admin',

            'email'=>'locked@test.com',

            'password'=>'password',

            'role'=>'admin',

            'status'=>'active',

            'failed_login_attempts'=>5,

            'locked_until'=>now()->addMinutes(10),

            'last_failed_login_at'=>now(),

        ]);



        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->postJson(

                '/api/v1/admin/admins/'.$target->id.'/unlock'

            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true,

                'message'=>'Admin account unlocked successfully.'

            ]);



        $target->refresh();



        $this->assertSame(

            0,

            $target->failed_login_attempts

        );



        $this->assertNull(

            $target->locked_until

        );



        $this->assertNull(

            $target->last_failed_login_at

        );

    }





}