<?php

namespace Tests\Feature;


use App\Models\Admin;
use App\Models\Permission;
use App\Models\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;



class AdminUserTest extends TestCase
{

    use RefreshDatabase;



    private function createAdmin()
    {


        $permission = Permission::firstOrCreate([

            'name'=>'manage_users'

        ]);



        $admin = Admin::create([

            'name'=>'Test Admin',

            'email'=>'admin@test.com',

            'password'=>'password',

            'role'=>'admin',

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
     * Admin can view users
     */
    public function test_admin_can_view_users()
    {


        [$admin,$token] =
            $this->createAdmin();



        User::factory()->create([
            'name'=>'Test User',
            'email'=>'user@test.com'
        ]);



        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->getJson(
                '/api/v1/admin/users'
            );




        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);

    }






    /**
     * Admin can view single user
     */
    public function test_admin_can_view_single_user()
    {


        [$admin,$token] =
            $this->createAdmin();



        $user = User::factory()->create();




        $response = $this

            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )

            ->getJson(
                '/api/v1/admin/users/'.$user->id
            );




        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);

    }



}