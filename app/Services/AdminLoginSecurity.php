<?php

namespace App\Services;


use App\Models\Admin;


class AdminLoginSecurity
{


    public static function isLocked(Admin $admin): bool
    {

        if(!$admin->locked_until){

            return false;

        }


        return now()->lessThan(
            $admin->locked_until
        );

    }





    public static function failed(Admin $admin): void
    {


        $attempts = $admin->failed_login_attempts + 1;



        $data=[

            'failed_login_attempts'=>$attempts,

            'last_failed_login_at'=>now(),

        ];




        if($attempts >= 5){


            $data['locked_until'] =
                now()->addMinutes(15);


        }



        $admin->update($data);


    }





    public static function success(Admin $admin): void
    {


        $admin->update([

            'failed_login_attempts'=>0,

            'locked_until'=>null,

        ]);


    }


}