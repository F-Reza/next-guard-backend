<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\Admin;
use App\Models\AdminSession;
use App\Models\AdminActivityLog;

use Illuminate\Http\JsonResponse;


class AdminSecurityDashboardController extends Controller
{


    /**
     * Security Dashboard
     */
    public function index(): JsonResponse
    {


        /*
        |--------------------------------------------------------------------------
        | Total Admins
        |--------------------------------------------------------------------------
        */


        $totalAdmins = Admin::count();







        /*
        |--------------------------------------------------------------------------
        | Locked Accounts
        |--------------------------------------------------------------------------
        */


        $lockedAccounts = Admin::whereNotNull(
            'locked_until'
        )
        ->where(
            'locked_until',
            '>',
            now()
        )
        ->count();







        /*
        |--------------------------------------------------------------------------
        | Active Sessions
        |--------------------------------------------------------------------------
        */


        $activeSessions = AdminSession::where(
            'expires_at',
            '>',
            now()
        )
        ->count();







        /*
        |--------------------------------------------------------------------------
        | Failed Login Today
        |--------------------------------------------------------------------------
        */


        $failedLoginToday = AdminActivityLog::where(
            'action',
            'ADMIN_LOGIN_FAILED'
        )
        ->whereDate(
            'created_at',
            today()
        )
        ->count();








        /*
        |--------------------------------------------------------------------------
        | Critical Events Today
        |--------------------------------------------------------------------------
        */


        $criticalEventsToday = AdminActivityLog::where(
            'severity',
            'critical'
        )
        ->whereDate(
            'created_at',
            today()
        )
        ->count();









        /*
        |--------------------------------------------------------------------------
        | Recent Security Events
        |--------------------------------------------------------------------------
        */


        $recentEvents = AdminActivityLog::with(
            'admin:id,name,email,role'
        )
        ->whereIn(

            'action',

            [

                'ADMIN_LOGIN_FAILED',

                'ADMIN_LOGIN_BLOCKED',

                'ADMIN_ACCOUNT_LOCKED',

                'ADMIN_PERMISSION_UPDATED',

                'ADMIN_PASSWORD_CHANGED'

            ]

        )
        ->latest()
        ->limit(10)
        ->get();








        return response()->json([


            'success'=>true,


            'message'=>'Admin security dashboard retrieved.',



            'data'=>[



                'total_admins'=>$totalAdmins,


                'active_sessions'=>$activeSessions,


                'locked_accounts'=>$lockedAccounts,


                'failed_login_today'=>$failedLoginToday,


                'critical_events_today'=>$criticalEventsToday,


                'recent_security_events'=>$recentEvents



            ]


        ]);



    }


}