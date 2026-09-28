<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\Device;

use App\Services\DeviceEnrollmentService;

use App\Services\DeviceLimitService;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Validator;

use Throwable;



class DeviceController extends Controller
{


    public function __construct(
        protected DeviceEnrollmentService $deviceService
    ){}




    /**
     * Enroll Device
     */
    public function enroll(
        Request $request
    ): JsonResponse
    {


        $validator = Validator::make(
            $request->all(),
            [

                'device_uuid'=>[
                    'required',
                    'string',
                    'max:255'
                ],


                'platform'=>[
                    'required',
                    'string',
                    'in:android'
                ],


                'model'=>[
                    'nullable',
                    'string',
                    'max:120'
                ],


                'manufacturer'=>[
                    'nullable',
                    'string',
                    'max:120'
                ],


                'android_version'=>[
                    'nullable',
                    'string',
                    'max:50'
                ],


                'app_version'=>[
                    'nullable',
                    'string',
                    'max:50'
                ],


                'management_mode'=>[
                    'nullable',
                    'string',
                    'in:standard,managed'
                ],


            ]
        );





        if($validator->fails()){


            return response()->json([

                'success'=>false,

                'message'=>'Validation failed.',

                'errors'=>$validator->errors()

            ],422);


        }






        try{


            $user = auth('api')->user();




            /*
            |--------------------------------------------------------------------------
            | Device Limit Check
            |--------------------------------------------------------------------------
            */


            $deviceExists = $user
                ->devices()
                ->where(
                    'device_uuid_hash',
                    hash(
                        'sha256',
                        $request->device_uuid
                    )
                )
                ->exists();





            if(
                !$deviceExists &&
                !DeviceLimitService::canAdd($user)
            ){


                return response()->json([


                    'success'=>false,


                    'message'=>'Device limit reached.',


                    'data'=>DeviceLimitService::info(
                        $user
                    )


                ],403);



            }







            $result =
                $this->deviceService->enroll(

                    $user,

                    $request->all(),

                    $request->ip(),

                    $request->userAgent()

                );









            return response()->json([


                'success'=>true,


                'message'=>

                    $result['is_new']

                    ? 'Device enrolled successfully.'

                    : 'Device already enrolled. Updated successfully.',




                'data'=>[


                    'device'=>

                        $this->deviceData(
                            $result['device']
                        ),



                    'refresh_token'=>

                        $result['refresh_token'],



                    'is_new'=>

                        $result['is_new']



                ]


            ],

            $result['is_new']

            ? 201

            : 200

            );




        }
        catch(Throwable $e){


            report($e);



            return response()->json([


                'success'=>false,


                'message'=>'Device enrollment failed.'



            ],500);



        }



    }









    /**
     * Get Devices
     */
    public function index(): JsonResponse
    {


        $user = auth('api')->user();



        $devices = $user
            ->devices()
            ->latest()
            ->get()
            ->map(

                fn(Device $device)=>

                $this->deviceData($device)

            );




        return response()->json([


            'success'=>true,


            'message'=>'Devices retrieved successfully.',


            'data'=>[

                'devices'=>$devices

            ]



        ]);



    }









    /**
     * Show Device
     */
    public function show(
        int $id
    ): JsonResponse
    {


        $device = auth('api')
            ->user()
            ->devices()
            ->where(
                'id',
                $id
            )
            ->first();





        if(!$device){


            return response()->json([

                'success'=>false,

                'message'=>'Device not found.'

            ],404);



        }





        return response()->json([


            'success'=>true,


            'message'=>'Device retrieved successfully.',


            'data'=>[

                'device'=>
                    $this->deviceData($device)

            ]


        ]);



    }









    /**
     * Heartbeat
     */
    public function heartbeat(
        Request $request,
        int $id
    ): JsonResponse
    {


    $request->validate([

        'app_version'=>[
            'nullable',
            'string',
            'max:50'
        ]

    ]);



    $device = auth('api')
        ->user()
        ->devices()
        ->where(
            'id',
            $id
        )
        ->first();





        if(!$device){


            return response()->json([

                'success'=>false,

                'message'=>'Device not found.'

            ],404);



        }






        if($device->isRevoked()){


            return response()->json([


                'success'=>false,


                'message'=>'Device revoked.'


            ],403);



        }





        $device->update([

            'status'=>'active',

            'last_seen_at'=>now(),

            'app_version'=>
                $request->app_version
                ??
                $device->app_version,

        ]);






        return response()->json([


            'success'=>true,


            'message'=>'Device heartbeat updated.',


            'data'=>[


                'device_id'=>$device->id,


                'status'=>$device->status,


                'last_seen_at'=>$device->last_seen_at


            ]


        ]);



    }








    /**
     * Format Device
     */
    private function deviceData(
        Device $device
    ): array
    {


        return [


            'id'=>$device->id,


            'name'=>$device->name,


            'platform'=>$device->platform,


            'model'=>$device->model,


            'manufacturer'=>$device->manufacturer,


            'android_version'=>$device->android_version,


            'app_version'=>$device->app_version,


            'management_mode'=>$device->management_mode,


            'status'=>$device->status,


            'last_seen_at'=>$device->last_seen_at,


            'created_at'=>$device->created_at,


            'updated_at'=>$device->updated_at,


        ];



    }




}