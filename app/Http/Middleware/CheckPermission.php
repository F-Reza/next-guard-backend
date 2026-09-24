<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class CheckPermission
{


    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response
    {


        $admin = auth('admin')->user();



        if(!$admin){


            return response()->json([

                'success'=>false,

                'message'=>'Unauthenticated admin.'

            ],401);


        }






        /*
        |--------------------------------------------------------------------------
        | Super Admin Full Access
        |--------------------------------------------------------------------------
        */


        if($admin->role === 'super_admin'){


            return $next($request);


        }







        /*
        |--------------------------------------------------------------------------
        | Permission Check
        |--------------------------------------------------------------------------
        */


        $hasPermission = $admin
            ->permissions()
            ->where('name',$permission)
            ->exists();







        if(!$hasPermission){



            return response()->json([


                'success'=>false,


                'message'=>'Permission denied.',


                'required_permission'=>$permission


            ],403);



        }







        return $next($request);


    }


}