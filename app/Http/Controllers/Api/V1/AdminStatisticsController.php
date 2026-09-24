<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Device;
use App\Models\TrialEntitlement;
use App\Models\Subscription;
use App\Models\LicenseCode;
use Illuminate\Http\JsonResponse;


class AdminStatisticsController extends Controller
{


    /**
     * Admin dashboard statistics
     */
    public function index(): JsonResponse
    {


        return response()->json([

            'success' => true,

            'message' => 'Admin statistics retrieved.',


            'data' => [


                'users' => [

                    'total' => User::count(),

                    'active' => User::where(
                        'status',
                        'active'
                    )->count(),

                ],



                'devices' => [

                    'total' => Device::count(),

                    'active' => Device::where(
                        'status',
                        'active'
                    )->count(),

                ],



                'trials' => [

                    'active' => TrialEntitlement::where(
                        'status',
                        'active'
                    )->count(),


                    'expired' => TrialEntitlement::where(
                        'status',
                        'expired'
                    )->count(),

                ],




                'subscriptions' => [

                    'active' => Subscription::where(
                        'status',
                        'active'
                    )->count(),


                    'expired' => Subscription::where(
                        'status',
                        'expired'
                    )->count(),

                ],




                'licenses' => [

                    'total' => LicenseCode::count(),


                    'used' => LicenseCode::where(
                        'status',
                        'used'
                    )->count(),


                    'available' => LicenseCode::where(
                        'status',
                        'active'
                    )->count(),

                ],


            ]

        ]);

    }


}