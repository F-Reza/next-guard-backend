<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/*
|--------------------------------------------------------------------------
| Notification System API Documentation
|--------------------------------------------------------------------------
*/



#[OA\Get(
    path:"/devices/{id}/protection/notifications",

    tags:["Notification System"],

    summary:"Get protection notifications",


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

            description:"Notification list"

        )


    ]

)]









#[OA\Put(
    path:"/devices/{device}/protection/notifications/{id}/read",

    tags:["Notification System"],

    summary:"Mark protection notification as read",


    security:[
        [
            "bearerAuth"=>[]
        ]
    ],



    parameters:[


        new OA\Parameter(

            name:"device",

            in:"path",

            required:true,


            schema:new OA\Schema(

                type:"integer"

            )

        ),



        new OA\Parameter(

            name:"id",

            in:"path",

            required:true,


            schema:new OA\Schema(

                type:"integer"

            )

        )


    ],



    responses:[


        new OA\Response(

            response:200,

            description:"Notification marked as read"

        )


    ]

)]









#[OA\Get(
    path:"/admin/notifications",

    tags:["Notification System"],

    summary:"Admin notifications",


    security:[
        [
            "bearerAuth"=>[]
        ]
    ],



    responses:[


        new OA\Response(

            response:200,

            description:"Admin notification list"

        )


    ]

)]









#[OA\Get(
    path:"/admin/notifications/unread",

    tags:["Notification System"],

    summary:"Unread admin notifications",


    security:[
        [
            "bearerAuth"=>[]
        ]
    ],



    responses:[


        new OA\Response(

            response:200,

            description:"Unread notifications"

        )


    ]

)]









#[OA\Put(
    path:"/admin/notifications/{id}/read",

    tags:["Notification System"],

    summary:"Mark admin notification read",


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

                type:"integer"

            )

        )

    ],



    responses:[


        new OA\Response(

            response:200,

            description:"Notification read"

        )


    ]

)]








class NotificationApi
{

}