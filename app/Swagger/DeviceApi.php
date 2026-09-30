<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


#[OA\Post(
    path: "/devices/enroll",
    tags: ["Device Management"],
    summary: "Enroll new device",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],

    requestBody: new OA\RequestBody(

        required:true,

        content:new OA\JsonContent(

            required:[
                "device_uuid_hash",
                "platform"
            ],

            properties:[

                new OA\Property(
                    property:"device_uuid_hash",
                    type:"string",
                    example:"a8f7c91d83..."
                ),

                new OA\Property(
                    property:"platform",
                    type:"string",
                    example:"android"
                )

            ]

        )
    ),

    responses:[

        new OA\Response(
            response:201,
            description:"Device enrolled successfully"
        ),

        new OA\Response(
            response:422,
            description:"Validation error"
        ),

        new OA\Response(
            response:401,
            description:"Unauthenticated"
        )

    ]

)]



#[OA\Get(
    path:"/devices",
    tags:["Device Management"],
    summary:"Get user devices",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],

    responses:[

        new OA\Response(
            response:200,
            description:"Device list"
        ),

        new OA\Response(
            response:401,
            description:"Unauthenticated"
        )

    ]

)]



#[OA\Get(
    path:"/devices/{id}",
    tags:["Device Management"],
    summary:"Get device details",

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
            description:"Device details"
        ),

        new OA\Response(
            response:404,
            description:"Device not found"
        )

    ]

)]



#[OA\Post(
    path:"/devices/{id}/heartbeat",
    tags:["Device Management"],
    summary:"Send device heartbeat",

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
            description:"Heartbeat updated"
        ),

        new OA\Response(
            response:401,
            description:"Unauthenticated"
        )

    ]

)]



#[OA\Patch(
    path:"/devices/{id}/rename",
    tags:["Device Management"],
    summary:"Rename device",

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

            required:[
                "name"
            ],

            properties:[

                new OA\Property(
                    property:"name",
                    type:"string",
                    example:"My Android Phone"
                )

            ]

        )

    ),

    responses:[

        new OA\Response(
            response:200,
            description:"Device renamed"
        )

    ]

)]



#[OA\Post(
    path:"/devices/{id}/revoke",
    tags:["Device Management"],
    summary:"Revoke device",

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
            description:"Device revoked"
        )

    ]

)]



#[OA\Post(
    path:"/devices/{id}/transfer",
    tags:["Device Management"],
    summary:"Transfer device",

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
                    property:"user_id",
                    type:"integer",
                    example:5
                )

            ]

        )

    ),

    responses:[

        new OA\Response(
            response:200,
            description:"Device transferred"
        )

    ]

)]



class DeviceApi
{

}