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
            schema:new OA\Schema(
                type:"integer",
                example:1
            )
        )

    ],


    responses:[

        new OA\Response(
            response:200,
            description:"Violation list"
        ),

        new OA\Response(
            response:404,
            description:"Device not found"
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

            schema:new OA\Schema(
                type:"integer"
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
            description:"Protection check result"
        ),


        new OA\Response(
            response:403,
            description:"Domain blocked"
        )


    ]

)]





class ViolationApi
{

}