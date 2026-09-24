<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;


class PermissionSeeder extends Seeder
{


    public function run(): void
    {


        $permissions = [


            [
                'name'=>'manage_users',
                'description'=>'Create, view and manage users'
            ],


            [
                'name'=>'manage_plans',
                'description'=>'Create and manage subscription plans'
            ],


            [
                'name'=>'manage_licenses',
                'description'=>'Generate and manage license codes'
            ],


            [
                'name'=>'manage_subscriptions',
                'description'=>'View and manage subscriptions'
            ],


            [
                'name'=>'manage_rules',
                'description'=>'Create and manage protection rules'
            ],


            [
                'name'=>'view_dashboard',
                'description'=>'View admin dashboard statistics'
            ],


            [
                'name'=>'view_logs',
                'description'=>'View admin activity logs'
            ],


        ];



        foreach($permissions as $permission){


            Permission::updateOrCreate(

                [
                    'name'=>$permission['name']
                ],

                $permission

            );


        }


    }

}