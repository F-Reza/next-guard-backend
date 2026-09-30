<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/*
|--------------------------------------------------------------------------
| Notification System API Documentation
|--------------------------------------------------------------------------
*/


#[OA\Get(
    path:"/admin/notifications",
    tags:["Notification System"],
    summary:"Get admin notifications",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],

    responses:[

        new OA\Response(
            response:200,
            description:"Admin notification list"
        ),

        new OA\Response(
            response:401,
            description:"Unauthenticated"
        )

    ]

)]





#[OA\Get(
    path:"/admin/notifications/unread",
    tags:["Notification System"],
    summary:"Get unread admin notifications",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],

    responses:[

        new OA\Response(
            response:200,
            description:"Unread notification list"
        ),

        new OA\Response(
            response:401,
            description:"Unauthenticated"
        )

    ]

)]





#[OA\Put(
    path:"/admin/notifications/{id}/read",
    tags:["Notification System"],
    summary:"Mark admin notification as read",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    parameters:[

        new OA\Parameter(
            name:"id",
            in:"path",
            required:true,

            schema:new OA\Schema(
                type:"integer",
                example:1
            )
        )

    ],


    responses:[

        new OA\Response(
            response:200,
            description:"Notification marked as read"
        ),

        new OA\Response(
            response:404,
            description:"Notification not found"
        )

    ]

)]





#[OA\Delete(
    path:"/admin/notifications/{id}",
    tags:["Notification System"],
    summary:"Delete admin notification",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    parameters:[

        new OA\Parameter(
            name:"id",
            in:"path",
            required:true,

            schema:new OA\Schema(
                type:"integer",
                example:1
            )
        )

    ],


    responses:[

        new OA\Response(
            response:200,
            description:"Notification deleted successfully"
        ),

        new OA\Response(
            response:404,
            description:"Notification not found"
        )

    ]

)]





class NotificationApi
{

}