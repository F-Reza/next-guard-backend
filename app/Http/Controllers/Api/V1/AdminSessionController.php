<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\AdminSession;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;



class AdminSessionController extends Controller
{


    /**
     * List current admin sessions
     */
    public function index(Request $request): JsonResponse
    {


        $admin = auth('admin')->user();



        if(!$admin){


            return response()->json([

                'success'=>false,

                'message'=>'Unauthenticated admin.'

            ],401);


        }




        $sessions = AdminSession::where(
                'admin_id',
                $admin->id
            )
            ->latest()
            ->get();



        return response()->json([


            'success'=>true,


            'message'=>'Admin sessions retrieved.',



            'data'=>[


                'sessions'=>$sessions


            ]



        ]);



    }







    /**
     * Revoke own session
     */
    public function destroy(
        int $id
    ): JsonResponse
    {


        $admin = auth('admin')->user();




        $session = AdminSession::where(

                'admin_id',

                $admin->id

            )
            ->where('id',$id)
            ->first();




        if(!$session){


            return response()->json([


                'success'=>false,


                'message'=>'Session not found.'


            ],404);



        }





        $session->delete();




        return response()->json([


            'success'=>true,


            'message'=>'Session revoked successfully.'


        ]);



    }






    /**
     * Logout all sessions
     */
    public function logoutAll(): JsonResponse
    {


        $admin = auth('admin')->user();



        AdminSession::where(

            'admin_id',

            $admin->id

        )->delete();





        auth('admin')->logout();




        return response()->json([


            'success'=>true,


            'message'=>'All sessions logged out successfully.'


        ]);



    }


}