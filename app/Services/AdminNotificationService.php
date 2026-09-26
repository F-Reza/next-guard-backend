<?php

namespace App\Services;


use App\Models\AdminNotification;
use App\Models\Admin;



class AdminNotificationService
{


    public static function send(

        Admin $admin,

        string $type,

        string $title,

        string $message,

        array $data=[]

    ): AdminNotification
    {


        return AdminNotification::create([


            'admin_id'=>$admin->id,


            'type'=>$type,


            'title'=>$title,


            'message'=>$message,


            'data'=>$data,


        ]);


    }


}