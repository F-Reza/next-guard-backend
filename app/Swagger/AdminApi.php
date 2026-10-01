<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/*
|--------------------------------------------------------------------------
| Admin System API Documentation
|--------------------------------------------------------------------------
*/


#[OA\Post(
    path:"/admin/login",

    tags:["Admin System"],

    summary:"Admin login",


    requestBody:new OA\RequestBody(

        required:true,

        content:new OA\JsonContent(

            required:[
                "email",
                "password"
            ],

            properties:[

                new OA\Property(
                    property:"email",
                    type:"string",
                    example:"admin@test.com"
                ),

                new OA\Property(
                    property:"password",
                    type:"string",
                    example:"password"
                )

            ]

        )

    ),


    responses:[


        new OA\Response(

            response:200,

            description:"Admin login successful",

            content:new OA\JsonContent(

                properties:[

                    new OA\Property(
                        property:"success",
                        type:"boolean",
                        example:true
                    ),

                    new OA\Property(
                        property:"token",
                        type:"string",
                        example:"eyJ0eXAiOiJKV1QiLCJhbGc..."
                    )

                ]

            )

        ),


        new OA\Response(

            response:401,

            description:"Invalid credentials",

            content:new OA\JsonContent(
                ref:"#/components/schemas/ErrorResponse"
            )

        )

    ]

)]





#[OA\Get(
    path:"/admin/profile",

    tags:["Admin System"],

    summary:"Get admin profile",


    security:[

        [

            "bearerAuth"=>[]

        ]

    ],


    responses:[


        new OA\Response(

            response:200,

            description:"Admin profile",

            content:new OA\JsonContent(
                ref:"#/components/schemas/User"
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
    path:"/admin/dashboard",

    tags:["Admin System"],

    summary:"Admin dashboard",


    security:[

        [

            "bearerAuth"=>[]

        ]

    ],


    responses:[


        new OA\Response(

            response:200,

            description:"Dashboard statistics"

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
    path:"/admin/statistics",

    tags:["Admin System"],

    summary:"Admin statistics",


    security:[

        [

            "bearerAuth"=>[]

        ]

    ],


    responses:[


        new OA\Response(

            response:200,

            description:"System statistics"

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
    path:"/admin/users",

    tags:["Admin System"],

    summary:"Get users",


    security:[

        [

            "bearerAuth"=>[]

        ]

    ],


    responses:[


        new OA\Response(

            response:200,

            description:"User list",

            content:new OA\JsonContent(

                type:"array",

                items:new OA\Items(

                    ref:"#/components/schemas/User"

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
    path:"/admin/activity-logs",

    tags:["Admin System"],

    summary:"Get activity logs",


    security:[

        [

            "bearerAuth"=>[]

        ]

    ],


    responses:[


        new OA\Response(

            response:200,

            description:"Activity logs"

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
    path:"/admin/security",

    tags:["Admin System"],

    summary:"Security dashboard",


    security:[

        [

            "bearerAuth"=>[]

        ]

    ],


    responses:[


        new OA\Response(

            response:200,

            description:"Security information"

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





#[OA\Post(
    path:"/admin/logout",

    tags:["Admin System"],

    summary:"Admin logout",


    security:[

        [

            "bearerAuth"=>[]

        ]

    ],


    responses:[


        new OA\Response(

            response:200,

            description:"Logout successful",

            content:new OA\JsonContent(

                properties:[

                    new OA\Property(
                        property:"success",
                        type:"boolean",
                        example:true
                    ),

                    new OA\Property(
                        property:"message",
                        type:"string",
                        example:"Logged out successfully"
                    )

                ]

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





class AdminApi
{

}