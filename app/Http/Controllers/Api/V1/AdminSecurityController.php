<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\Admin;

use Illuminate\Http\JsonResponse;



class AdminSecurityController extends Controller
{


    /**
     * Security Dashboard
     */
    public function index(): JsonResponse
    {


        $admins = Admin::query()

            ->where('role','!=','super_admin')

            ->select([

                'id',

                'name',

                'email',

                'role',

                'status',

                'failed_login_attempts',

                'locked_until',

                'last_failed_login_at',

                'last_login_at',

            ])

            ->latest()

            ->get();





        $lockedAdmins = $admins
            ->filter(function($admin){


                return $admin->locked_until
                    &&
                    now()->lessThan(
                        $admin->locked_until
                    );


            })

            ->values();







        return response()->json([



            'success'=>true,



            'message'=>'Admin security status retrieved.',




            'data'=>[



                'total_admins'=>$admins->count(),



                'locked_accounts'=>$lockedAdmins->count(),




                'locked_admins'=>$lockedAdmins,




                'admins'=>$admins



            ]



        ]);



    }



}