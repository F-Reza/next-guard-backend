<?php

namespace Tests\Feature;


use App\Models\Admin;
use App\Models\Permission;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;



class AdminSecurityTest extends TestCase
{

    use RefreshDatabase;



    private function createAdmin()
    {

        $manageAdmins = Permission::firstOrCreate([

            'name'=>'manage_admins'

        ]);



        $viewLogs = Permission::firstOrCreate([

            'name'=>'view_logs'

        ]);



        $admin = Admin::create([

            'name'=>'Security Admin',

            'email'=>'security@test.com',

            'password'=>'password',

            'role'=>'super_admin',

            'status'=>'active',

        ]);



        $admin->permissions()->attach([

            $manageAdmins->id,

            $viewLogs->id

        ]);



        $token = auth('admin')

            ->login($admin);



        return [

            $admin,

            $token

        ];

    }





    /**
     * Admin can view security
     */
    public function test_admin_can_view_security()
    {

        [$admin,$token] =
            $this->createAdmin();



        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->getJson(

                '/api/v1/admin/security'

            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);

    }





    /**
     * Admin can view activity logs
     */
    public function test_admin_can_view_activity_logs()
    {

        [$admin,$token] =
            $this->createAdmin();



        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->getJson(

                '/api/v1/admin/activity-logs'

            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);

    }





    /**
     * Admin can view security activity logs
     */
    public function test_admin_can_view_security_activity_logs()
    {

        [$admin,$token] =
            $this->createAdmin();



        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->getJson(

                '/api/v1/admin/activity-logs/security'

            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);

    }





    /**
     * Admin can view security dashboard
     */
    public function test_admin_can_view_security_dashboard()
    {

        [$admin,$token] =
            $this->createAdmin();



        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->getJson(

                '/api/v1/admin/security/dashboard'

            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);

    }



}