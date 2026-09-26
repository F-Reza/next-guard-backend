<?php

namespace App\Services;


use App\Models\Admin;
use App\Models\AdminActivityLog;

use Illuminate\Http\Request;

use Throwable;



class AdminActivityLogger
{


    /*
    |--------------------------------------------------------------------------
    | Severity Constants
    |--------------------------------------------------------------------------
    */


    public const INFO = 'info';

    public const WARNING = 'warning';

    public const CRITICAL = 'critical';




    /**
     * Create admin activity log
     */
    public static function log(

        string $action,

        string $description,

        ?Request $request = null,

        ?Admin $admin = null,

        string $severity = self::INFO,

        array $metadata = []

    ): ?AdminActivityLog
    {


        try {



            /*
            |--------------------------------------------------------------------------
            | Validate Severity
            |--------------------------------------------------------------------------
            */


            if(
                !in_array(
                    $severity,
                    [
                        self::INFO,
                        self::WARNING,
                        self::CRITICAL
                    ],
                    true
                )
            ){

                $severity = self::INFO;

            }






            /*
            |--------------------------------------------------------------------------
            | Resolve Admin Safely
            |--------------------------------------------------------------------------
            */


            if(!$admin){


                $authAdmin = auth('admin')->user();


                if($authAdmin instanceof Admin){

                    $admin = $authAdmin;

                }


            }






            /*
            |--------------------------------------------------------------------------
            | Create Log
            |--------------------------------------------------------------------------
            */


            return AdminActivityLog::create([



                'admin_id'=>$admin?->id,


                'action'=>$action,


                'description'=>$description,


                'severity'=>$severity,



                'ip_address'=>
                    $request?->ip(),



                'user_agent'=>
                    $request?->userAgent(),



                'device'=>
                    self::device($request),



                'browser'=>
                    self::browser($request),



                'os'=>
                    self::os($request),



                'session_id'=>
                    self::sessionId(),



                'metadata'=>
                    $metadata,


            ]);





        }catch(Throwable $e){


            /*
            |--------------------------------------------------------------------------
            | Never Break Application
            |--------------------------------------------------------------------------
            */


            report($e);


            return null;


        }


    }








    /**
     * Get Session ID
     */
    private static function sessionId(): ?string
    {


        try {


            if(app()->bound('session')){


                return session()->getId();


            }


        }catch(Throwable $e){


        }



        return null;


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
            $request->userAgent() ?? ''
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
            $request->userAgent() ?? ''
        );




        return match(true){


            str_contains($agent,'edg')
                => 'Edge',



            str_contains($agent,'chrome')
                && !str_contains($agent,'edg')
                => 'Chrome',



            str_contains($agent,'firefox')
                => 'Firefox',



            str_contains($agent,'safari')
                && !str_contains($agent,'chrome')
                => 'Safari',



            default
                => 'Unknown',

        };


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
            $request->userAgent() ?? ''
        );





        return match(true){



            str_contains($agent,'android')
                => 'Android',



            str_contains($agent,'iphone')
                => 'iOS',



            str_contains($agent,'windows')
                => 'Windows',



            str_contains($agent,'mac')
                => 'MacOS',



            str_contains($agent,'linux')
                => 'Linux',



            default
                => 'Unknown',


        };


    }




}