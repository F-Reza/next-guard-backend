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
        | Super Admin Bypass
        |--------------------------------------------------------------------------
        */

        if($admin->role === 'super_admin'){

            return $next($request);

        }




        $hasPermission = $admin
            ->permissions()
            ->where(
                'name',
                $permission
            )
            ->exists();




        if(!$hasPermission){

            return response()->json([

                'success'=>false,

                'message'=>'Permission denied.'

            ],403);

        }



        return $next($request);

    }


}