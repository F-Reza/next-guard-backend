<?php

namespace Tests\Feature;


use App\Models\Admin;
use App\Models\Permission;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;



class AdminPermissionTest extends TestCase
{

    use RefreshDatabase;



    private function createSuperAdmin()
    {

        $permission = Permission::firstOrCreate([

            'name'=>'manage_admins'

        ]);



        $admin = Admin::create([

            'name'=>'Super Admin',

            'email'=>'super-permission@test.com',

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
     * Admin can view permissions
     */
    public function test_admin_can_view_permissions()
    {

        [$admin,$token] =
            $this->createSuperAdmin();



        Permission::firstOrCreate([

            'name'=>'view_logs'

        ]);



        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->getJson(

                '/api/v1/admin/permissions'

            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true

            ]);

    }





    /**
     * Super admin can assign permissions
     */
    public function test_super_admin_can_assign_permissions()
    {

        [$admin,$token] =
            $this->createSuperAdmin();



        $target = Admin::create([

            'name'=>'Target Admin',

            'email'=>'target-permission@test.com',

            'password'=>'password',

            'role'=>'admin',

            'status'=>'active',

        ]);



        $permission = Permission::firstOrCreate([

            'name'=>'view_logs'

        ]);



        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->postJson(

                '/api/v1/admin/admins/'.$target->id.'/permissions',

                [

                    'permissions'=>[

                        $permission->id

                    ]

                ]

            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true,

                'message'=>'Admin permissions updated.'

            ]);



        $this->assertDatabaseHas(

            'admin_permissions',

            [

                'admin_id'=>$target->id,

                'permission_id'=>$permission->id

            ]

        );

    }





    /**
     * Super admin can remove permission
     */
    public function test_super_admin_can_remove_permission()
    {

        [$admin,$token] =
            $this->createSuperAdmin();



        $target = Admin::create([

            'name'=>'Target Admin',

            'email'=>'target-remove@test.com',

            'password'=>'password',

            'role'=>'admin',

            'status'=>'active',

        ]);



        $permission = Permission::firstOrCreate([

            'name'=>'view_logs'

        ]);



        $target->permissions()->attach(

            $permission->id

        );



        $response = $this

            ->withHeader(

                'Authorization',

                'Bearer '.$token

            )

            ->deleteJson(

                '/api/v1/admin/admins/'.$target->id.'/permissions/'.$permission->id

            );



        $response

            ->assertStatus(200)

            ->assertJson([

                'success'=>true,

                'message'=>'Permission removed successfully.'

            ]);



        $this->assertDatabaseMissing(

            'admin_permissions',

            [

                'admin_id'=>$target->id,

                'permission_id'=>$permission->id

            ]

        );

    }



}