<?php

namespace App\Services;


use App\Models\Admin;
use App\Models\AdminNotification;



class AdminNotificationService
{


    public static function send(

        Admin $admin,

        string $type,

        string $title,

        string $message,

        array $metadata = []

    ): AdminNotification
    {


        return AdminNotification::create([


            'admin_id'=>$admin->id,


            'type'=>$type,


            'title'=>$title,


            'message'=>$message,


            'metadata'=>$metadata,


        ]);


    }



    public static function markRead(
        AdminNotification $notification
    ): bool
    {

        return $notification->update([

            'is_read'=>true

        ]);

    }



    public static function delete(
        AdminNotification $notification
    ): bool
    {

        return $notification->delete();

    }



}