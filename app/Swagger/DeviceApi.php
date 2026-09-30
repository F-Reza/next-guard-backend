<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;



#[OA\Post(
    path:"/devices/enroll",
    tags:["Device Management"],
    summary:"Enroll new device",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    requestBody:new OA\RequestBody(

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
            description:"Device enrolled successfully",

            content:new OA\JsonContent(
                ref:"#/components/schemas/Device"
            )
        ),



        new OA\Response(
            response:422,
            description:"Validation error",

            content:new OA\JsonContent(
                ref:"#/components/schemas/ErrorResponse"
            )
        ),



        new OA\Response(
            response:401,
            description:"Unauthenticated",

            content:new OA\JsonContent(
                ref:"#/components/schemas/ErrorResponse"
            )
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
            description:"Device list",

            content:new OA\JsonContent(

                type:"array",

                items:new OA\Items(
                    ref:"#/components/schemas/Device"
                )

            )
        ),



        new OA\Response(
            response:401,
            description:"Unauthenticated",

            content:new OA\JsonContent(
                ref:"#/components/schemas/ErrorResponse"
            )
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

            schema:new OA\Schema(
                type:"integer",
                example:1
            )
        )


    ],



    responses:[


        new OA\Response(
            response:200,

            description:"Device details",

            content:new OA\JsonContent(
                ref:"#/components/schemas/Device"
            )
        ),



        new OA\Response(
            response:404,

            description:"Device not found",

            content:new OA\JsonContent(
                ref:"#/components/schemas/ErrorResponse"
            )
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

            description:"Heartbeat updated",

            content:new OA\JsonContent(
                ref:"#/components/schemas/Device"
            )
        ),



        new OA\Response(
            response:401,

            description:"Unauthenticated",

            content:new OA\JsonContent(
                ref:"#/components/schemas/ErrorResponse"
            )
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

            description:"Device renamed",

            content:new OA\JsonContent(
                ref:"#/components/schemas/Device"
            )
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

            description:"Device revoked",

            content:new OA\JsonContent(
                ref:"#/components/schemas/Device"
            )
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

            description:"Device transferred",

            content:new OA\JsonContent(
                ref:"#/components/schemas/Device"
            )
        )


    ]

)]





class DeviceApi
{

}