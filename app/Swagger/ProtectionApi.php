<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/*
|--------------------------------------------------------------------------
| Protection Engine API Documentation
|--------------------------------------------------------------------------
*/


#[OA\Get(
    path:"/devices/{id}/protection/status",
    tags:["Protection Engine"],
    summary:"Get device protection status",

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
            description:"Protection status"
        ),

        new OA\Response(
            response:401,
            description:"Unauthenticated"
        ),

        new OA\Response(
            response:404,
            description:"Device not found"
        )

    ]

)]



#[OA\Post(
    path:"/devices/{id}/protection/update",
    tags:["Protection Engine"],
    summary:"Update device protection settings",

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
                    property:"protection_enabled",
                    type:"boolean",
                    example:true
                ),


                new OA\Property(
                    property:"mode",
                    type:"string",
                    example:"strict"
                )


            ]

        )

    ),


    responses:[


        new OA\Response(
            response:200,
            description:"Protection updated"
        ),


        new OA\Response(
            response:422,
            description:"Validation error"
        )

    ]

)]




#[OA\Post(
    path:"/devices/{id}/protection/sync",
    tags:["Protection Engine"],
    summary:"Sync protection rules",

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
            description:"Protection sync completed"
        )

    ]

)]




#[OA\Get(
    path:"/devices/{id}/protection-rules",
    tags:["Protection Engine"],
    summary:"Get device protection rules",

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
            description:"Protection rules list"
        )

    ]

)]




#[OA\Get(
    path:"/devices/{id}/protection/violations",
    tags:["Protection Engine"],
    summary:"Get protection violations",

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
            description:"Violation history"
        )

    ]

)]




#[OA\Get(
    path:"/devices/{id}/protection/summary",
    tags:["Protection Engine"],
    summary:"Protection summary",

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
            description:"Protection summary data"
        )

    ]

)]




#[OA\Get(
    path:"/devices/{id}/protection/analytics",
    tags:["Protection Engine"],
    summary:"Protection analytics",

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
            description:"Analytics data"
        )

    ]

)]




#[OA\Get(
    path:"/devices/{id}/protection/notifications",
    tags:["Protection Engine"],
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
                type:"integer"
            )
        )

    ],

    responses:[

        new OA\Response(
            response:200,
            description:"Protection notifications"
        )

    ]

)]




#[OA\Put(
    path:"/devices/{device}/protection/notifications/{id}/read",
    tags:["Protection Engine"],
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



class ProtectionApi
{

}