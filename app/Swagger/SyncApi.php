<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/*
|--------------------------------------------------------------------------
| Protection Sync API Documentation
|--------------------------------------------------------------------------
*/


#[OA\Get(
    path:"/device/protection/sync",
    tags:["Sync System"],
    summary:"Get protection sync data",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],

    responses:[

        new OA\Response(
            response:200,
            description:"Current protection sync data"
        ),

        new OA\Response(
            response:401,
            description:"Unauthenticated"
        )

    ]

)]



#[OA\Get(
    path:"/devices/{id}/protection/current-sync",
    tags:["Sync System"],
    summary:"Get device current sync status",

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
            description:"Device ID",
            schema:new OA\Schema(
                type:"integer",
                example:1
            )
        )

    ],


    responses:[

        new OA\Response(
            response:200,
            description:"Current sync information"
        ),

        new OA\Response(
            response:404,
            description:"Device not found"
        )

    ]

)]




#[OA\Get(
    path:"/devices/{id}/protection/sync-history",
    tags:["Sync System"],
    summary:"Get protection sync history",

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
            description:"Sync history list"
        )

    ]

)]





#[OA\Post(
    path:"/devices/{id}/protection/ack",
    tags:["Sync System"],
    summary:"Acknowledge protection sync",

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


    requestBody:new OA\RequestBody(

        required:true,

        content:new OA\JsonContent(

            properties:[


                new OA\Property(
                    property:"sync_id",
                    type:"integer",
                    example:10
                ),


                new OA\Property(
                    property:"status",
                    type:"string",
                    example:"completed"
                )


            ]

        )

    ),



    responses:[

        new OA\Response(
            response:200,
            description:"Sync acknowledged"
        )

    ]

)]





#[OA\Post(
    path:"/devices/{device}/protection/sync/{id}/retry",
    tags:["Sync System"],
    summary:"Retry failed protection sync",

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
            description:"Sync retry started"
        ),

        new OA\Response(
            response:422,
            description:"Retry failed"
        )

    ]

)]




#[OA\Get(
    path:"/devices/{id}/events",
    tags:["Sync System"],
    summary:"Get device events",

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
            description:"Device event list"
        )

    ]

)]




#[OA\Get(
    path:"/devices/{id}/security-events",
    tags:["Sync System"],
    summary:"Get device security events",

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
            description:"Security event list"
        )

    ]

)]



class SyncApi
{

}