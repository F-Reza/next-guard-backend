<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LicenseCode;
use Illuminate\Http\JsonResponse;


class AdminLicenseController extends Controller
{


    public function generate(): JsonResponse
    {

        return response()->json([

            'success'=>true,

            'message'=>'License generation endpoint ready.',

        ]);

    }




    public function index(): JsonResponse
    {

        $licenses = LicenseCode::latest()->get();


        return response()->json([

            'success'=>true,

            'message'=>'License codes retrieved.',

            'data'=>[

                'licenses'=>$licenses

            ]

        ]);

    }


}