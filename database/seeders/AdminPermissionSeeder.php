<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Permission;


class AdminPermissionSeeder extends Seeder
{


    public function run(): void
    {


        /*
        |--------------------------------------------------------------------------
        | Get Super Admin
        |--------------------------------------------------------------------------
        */


        $admin = Admin::where(
            'role',
            'super_admin'
        )->first();



        if(!$admin){

            return;

        }




        /*
        |--------------------------------------------------------------------------
        | All Permissions
        |--------------------------------------------------------------------------
        */


        $permissions = Permission::all();




        /*
        |--------------------------------------------------------------------------
        | Attach Permissions
        |--------------------------------------------------------------------------
        */


        $admin->permissions()
            ->sync(
                $permissions->pluck('id')
            );



    }


}