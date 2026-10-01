<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/**
 * Authentication API Documentation
 */


#[OA\Post(
    path: "/auth/register",
    tags: ["Authentication"],
    summary: "User Registration",

    requestBody: new OA\RequestBody(
        required: true,

        content: new OA\JsonContent(

            required: [
                "name",
                "email",
                "password"
            ],

            properties: [

                new OA\Property(
                    property: "name",
                    type: "string",
                    example: "John Doe"
                ),

                new OA\Property(
                    property: "email",
                    type: "string",
                    example: "user@test.com"
                ),

                new OA\Property(
                    property: "password",
                    type: "string",
                    example: "password"
                ),

            ]

        )
    ),


    responses: [

        new OA\Response(
            response:201,
            description:"User registered successfully",

            content:new OA\JsonContent(
                ref:"#/components/schemas/User"
            )
        ),


        new OA\Response(
            response:422,
            description:"Validation error",

            content:new OA\JsonContent(
                ref:"#/components/schemas/ErrorResponse"
            )
        )

    ]

)]



#[OA\Post(
    path: "/auth/login",
    tags:["Authentication"],
    summary:"User Login",


    requestBody:new OA\RequestBody(
        required:true,

        content:new OA\JsonContent(

            required:[
                "login",
                "password"
            ],


            properties:[


                new OA\Property(
                    property:"login",
                    type:"string",
                    example:"user@test.com"
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
            description:"Login success",

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
                    ),


                    new OA\Property(
                        property:"user",
                        ref:"#/components/schemas/User"
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
    path:"/auth/me",

    tags:["Authentication"],

    summary:"Get authenticated user",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],

    responses:[

        new OA\Response(
            response:200,

            description:"User profile",

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
                        example:"Authenticated user."
                    ),

                    new OA\Property(
                        property:"data",
                        type:"object",

                        properties:[

                            new OA\Property(
                                property:"user",
                                ref:"#/components/schemas/User"
                            )

                        ]
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





#[OA\Post(
    path:"/auth/logout",

    tags:["Authentication"],

    summary:"Logout user",


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




class AuthApi
{

}