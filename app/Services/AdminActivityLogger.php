<?php

namespace App\Services;

use App\Models\AdminActivityLog;
use Illuminate\Http\Request;


class AdminActivityLogger
{


    public static function log(
        string $action,
        string $description,
        ?Request $request = null
    ): AdminActivityLog {


        $admin = auth('admin')->user();



        return AdminActivityLog::create([

            'admin_id'=>$admin?->id,

            'action'=>$action,

            'description'=>$description,

            'ip_address'=>$request?->ip(),

            'user_agent'=>$request?->userAgent(),

        ]);

    }


}