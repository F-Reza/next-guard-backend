<?php

namespace Tests\Feature;


use App\Models\Admin;
use App\Models\Permission;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;



class AdminManagementTest extends TestCase
{

    use RefreshDatabase;



    private function createSuperAdmin()
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
            $this->createSuperAdmin();



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
            $this->createSuperAdmin();




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
            $this->createSuperAdmin();



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




}