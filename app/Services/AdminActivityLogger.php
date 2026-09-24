<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Throwable;


class AdminActivityLogger
{


    /**
     * Create admin activity log
     */
    public static function log(
        string $action,
        string $description,
        ?Request $request = null,
        ?Admin $admin = null
    ): ?AdminActivityLog
    {


        try {


            /*
            |--------------------------------------------------------------------------
            | Get authenticated admin
            |--------------------------------------------------------------------------
            */


            $admin = $admin 
                ?? auth('admin')->user();





            return AdminActivityLog::create([


                /*
                |--------------------------------------------------------------------------
                | Nullable admin_id
                |--------------------------------------------------------------------------
                */

                'admin_id' => $admin?->id,



                /*
                |--------------------------------------------------------------------------
                | Activity
                |--------------------------------------------------------------------------
                */

                'action' => $action,


                'description' => $description,



                /*
                |--------------------------------------------------------------------------
                | Request Information
                |--------------------------------------------------------------------------
                */

                'ip_address' => $request?->ip(),


                'user_agent' => $request?->userAgent(),



            ]);



        } catch(Throwable $e) {



            /*
            |--------------------------------------------------------------------------
            | Never break main request because of logging failure
            |--------------------------------------------------------------------------
            */

            report($e);


            return null;


        }


    }




    /**
     * Get current admin id
     */
    private static function adminId(): ?int
    {

        return auth('admin')->id();

    }



}