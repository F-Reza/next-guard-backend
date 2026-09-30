<?php

namespace Tests\Feature;


use App\Models\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Permission;
use App\Models\AdminPermission;
use Tests\TestCase;



class AdminDashboardTest extends TestCase
{

    use RefreshDatabase;



    private function createAdmin()
    {

        $permission = Permission::firstOrCreate([

            'name'=>'view_dashboard'

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
     * Admin dashboard access
     */
    public function test_admin_can_view_dashboard()
    {


        [$admin,$token] =
            $this->createAdmin();




        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->getJson(

                '/api/v1/admin/dashboard'

            );





        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true,

                'message'=>'Admin dashboard data.'

            ]);

    }







    /**
     * Admin statistics access
     */
    public function test_admin_can_view_statistics()
    {


        [$admin,$token] =
            $this->createAdmin();




        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->getJson(

                '/api/v1/admin/statistics'

            );





        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);

    }


}