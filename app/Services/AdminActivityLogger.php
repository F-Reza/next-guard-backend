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

        ?Admin $admin = null,

        string $severity = 'info',

        array $metadata = []

    ): ?AdminActivityLog
    {


        try {



            /*
            |--------------------------------------------------------------------------
            | Get Admin
            |--------------------------------------------------------------------------
            */


            $admin = $admin
                ?? auth('admin')->user();







            return AdminActivityLog::create([



                /*
                |--------------------------------------------------------------------------
                | Admin
                |--------------------------------------------------------------------------
                */


                'admin_id'=>$admin?->id,





                /*
                |--------------------------------------------------------------------------
                | Activity
                |--------------------------------------------------------------------------
                */


                'action'=>$action,


                'description'=>$description,


                'severity'=>$severity,





                /*
                |--------------------------------------------------------------------------
                | Request Information
                |--------------------------------------------------------------------------
                */


                'ip_address'=>$request?->ip(),


                'user_agent'=>$request?->userAgent(),





                /*
                |--------------------------------------------------------------------------
                | Device Information
                |--------------------------------------------------------------------------
                */


                'device'=>self::device($request),


                'browser'=>self::browser($request),


                'os'=>self::os($request),





                /*
                |--------------------------------------------------------------------------
                | Session
                |--------------------------------------------------------------------------
                */


                'session_id'=>session()->getId(),





                /*
                |--------------------------------------------------------------------------
                | Extra Data
                |--------------------------------------------------------------------------
                */


                'metadata'=>$metadata,



            ]);




        } catch(Throwable $e) {



            /*
            |--------------------------------------------------------------------------
            | Logging must never break application
            |--------------------------------------------------------------------------
            */


            report($e);


            return null;


        }



    }








    /**
     * Detect Device
     */
    private static function device(
        ?Request $request
    ): ?string
    {


        if(!$request){

            return null;

        }



        $agent = strtolower(
            $request->userAgent()
        );




        if(
            str_contains(
                $agent,
                'mobile'
            )
        ){

            return 'Mobile';

        }




        return 'Desktop';



    }








    /**
     * Detect Browser
     */
    private static function browser(
        ?Request $request
    ): ?string
    {


        if(!$request){

            return null;

        }



        $agent = strtolower(
            $request->userAgent()
        );




        if(str_contains($agent,'edge')){

            return 'Edge';

        }



        if(str_contains($agent,'chrome')){

            return 'Chrome';

        }



        if(str_contains($agent,'firefox')){

            return 'Firefox';

        }



        if(str_contains($agent,'safari')){

            return 'Safari';

        }



        return 'Unknown';



    }








    /**
     * Detect Operating System
     */
    private static function os(
        ?Request $request
    ): ?string
    {


        if(!$request){

            return null;

        }



        $agent = strtolower(
            $request->userAgent()
        );




        if(str_contains($agent,'windows')){

            return 'Windows';

        }




        if(str_contains($agent,'android')){

            return 'Android';

        }




        if(str_contains($agent,'iphone')){

            return 'iOS';

        }




        if(str_contains($agent,'mac')){

            return 'MacOS';

        }




        if(str_contains($agent,'linux')){

            return 'Linux';

        }




        return 'Unknown';



    }








    /**
     * Current Admin ID
     */
    private static function adminId(): ?int
    {

        return auth('admin')->id();

    }



}