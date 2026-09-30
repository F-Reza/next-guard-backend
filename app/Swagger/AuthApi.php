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
            response: 201,
            description: "User registered successfully"
        ),

        new OA\Response(
            response: 422,
            description: "Validation error"
        )

    ]
)]



#[OA\Post(
    path: "/auth/login",
    tags: ["Authentication"],
    summary: "User Login",

    requestBody: new OA\RequestBody(
        required: true,

        content: new OA\JsonContent(

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
                ),

            ]

        )
    ),


    responses:[

        new OA\Response(
            response:200,
            description:"Login success"
        ),

        new OA\Response(
            response:401,
            description:"Invalid credentials"
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
            description:"User profile"
        ),

        new OA\Response(
            response:401,
            description:"Unauthenticated"
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
            description:"Logout successful"
        )

    ]

)]



class AuthApi
{

}