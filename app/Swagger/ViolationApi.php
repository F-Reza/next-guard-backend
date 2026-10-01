<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/*
|--------------------------------------------------------------------------
| Protection Violation API Documentation
|--------------------------------------------------------------------------
*/


#[OA\Get(
    path:"/devices/{id}/protection/violations",

    tags:["Violation System"],

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

            description:"Violation list",

            content:new OA\JsonContent(

                type:"array",

                items:new OA\Items(

                    type:"object"

                )

            )

        ),


        new OA\Response(

            response:401,

            description:"Unauthenticated",

            content:new OA\JsonContent(

                ref:"#/components/schemas/ErrorResponse"

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
    path:"/devices/{id}/protection/check",

    tags:["Violation System"],

    summary:"Check domain protection status",


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


    requestBody:new OA\RequestBody(

        required:true,

        content:new OA\JsonContent(

            required:[

                "domain"

            ],

            properties:[


                new OA\Property(

                    property:"domain",

                    type:"string",

                    example:"example.com"

                )

            ]

        )

    ),


    responses:[


        new OA\Response(

            response:200,

            description:"Protection check result",

            content:new OA\JsonContent(

                properties:[


                    new OA\Property(

                        property:"domain",

                        type:"string",

                        example:"example.com"

                    ),


                    new OA\Property(

                        property:"protected",

                        type:"boolean",

                        example:true

                    ),


                    new OA\Property(

                        property:"blocked",

                        type:"boolean",

                        example:false

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

        ),


        new OA\Response(

            response:403,

            description:"Domain blocked",

            content:new OA\JsonContent(

                ref:"#/components/schemas/ErrorResponse"

            )

        ),


        new OA\Response(

            response:404,

            description:"Device not found",

            content:new OA\JsonContent(

                ref:"#/components/schemas/ErrorResponse"

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





class ViolationApi
{

}