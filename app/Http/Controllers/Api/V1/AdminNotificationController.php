<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\AdminNotification;

use App\Services\AdminNotificationService;

use Illuminate\Http\Request;



class AdminNotificationController extends Controller
{



    /**
     * All Notifications
     */
    public function index()
    {


        $admin = auth('admin')->user();



        $notifications =
            $admin
            ->notifications()
            ->latest()
            ->paginate(20);



        return response()->json([


            'success'=>true,


            'message'=>'Notifications retrieved.',


            'data'=>$notifications


        ]);

    }






    /**
     * Unread Notifications
     */
    public function unread()
    {


        $admin = auth('admin')->user();



        $notifications =
            $admin
            ->notifications()
            ->where(
                'is_read',
                false
            )
            ->latest()
            ->get();



        return response()->json([


            'success'=>true,


            'message'=>'Unread notifications retrieved.',


            'data'=>$notifications


        ]);


    }








    /**
     * Mark Read
     */
    public function read(
        int $id
    )
    {


        $notification =
            AdminNotification::where(
                'id',
                $id
            )
            ->where(
                'admin_id',
                auth('admin')->id()
            )
            ->firstOrFail();




        AdminNotificationService::markRead(
            $notification
        );



        return response()->json([


            'success'=>true,


            'message'=>'Notification marked as read.'


        ]);

    }









    /**
     * Delete
     */
    public function destroy(
        int $id
    )
    {


        $notification =
            AdminNotification::where(
                'id',
                $id
            )
            ->where(
                'admin_id',
                auth('admin')->id()
            )
            ->firstOrFail();



        AdminNotificationService::delete(
            $notification
        );



        return response()->json([


            'success'=>true,


            'message'=>'Notification deleted.'


        ]);

    }




}