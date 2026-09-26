<?php

namespace App\Services;

use App\Models\Admin;


class AdminLoginSecurity
{


    /**
     * Check account locked
     */
    public static function isLocked(Admin $admin): bool
    {


        if(!$admin->locked_until){

            return false;

        }



        if(now()->greaterThanOrEqualTo($admin->locked_until)){


            self::unlock($admin);


            return false;

        }



        return true;


    }






    /**
     * Failed login attempt
     */
    public static function failed(Admin $admin): void
    {


        /*
        |--------------------------------------------------------------------------
        | Super Admin Bypass
        |--------------------------------------------------------------------------
        */

        if($admin->role === 'super_admin'){

            return;

        }



        $attempts = $admin->failed_login_attempts + 1;



        $data = [


            'failed_login_attempts'=>$attempts,


            'last_failed_login_at'=>now(),


        ];





        if($attempts >= 5){


            $data['locked_until'] =
                now()->addMinutes(15);


        }




        $admin->update($data);


    }







    /**
     * Successful login
     */
    public static function success(Admin $admin): void
    {


        if($admin->role === 'super_admin'){

            return;

        }



        $admin->update([


            'failed_login_attempts'=>0,


            'locked_until'=>null,


            'last_failed_login_at'=>null,


        ]);


    }







    /**
     * Unlock account
     */
    public static function unlock(Admin $admin): void
    {


        $admin->update([


            'failed_login_attempts'=>0,


            'locked_until'=>null,


        ]);


    }



}