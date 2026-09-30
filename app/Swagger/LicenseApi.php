<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/*
|--------------------------------------------------------------------------
| License System API Documentation
|--------------------------------------------------------------------------
*/



#[OA\Post(
    path:"/license-codes/redeem",
    tags:["License System"],
    summary:"Redeem license code",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    requestBody:new OA\RequestBody(

        required:true,

        content:new OA\JsonContent(

            required:[
                "code"
            ],

            properties:[


                new OA\Property(
                    property:"code",
                    type:"string",
                    example:"NG-PRO-ABC123"
                )


            ]

        )

    ),


    responses:[


        new OA\Response(
            response:200,
            description:"License redeemed successfully"
        ),


        new OA\Response(
            response:422,
            description:"Invalid license code"
        )

    ]

)]







#[OA\Post(
    path:"/licenses/redeem",
    tags:["License System"],
    summary:"Redeem license",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    requestBody:new OA\RequestBody(

        required:true,

        content:new OA\JsonContent(

            required:[
                "license_code"
            ],


            properties:[


                new OA\Property(
                    property:"license_code",
                    type:"string",
                    example:"NG-LICENSE-001"
                )


            ]

        )

    ),



    responses:[


        new OA\Response(
            response:200,
            description:"License activated"
        ),


        new OA\Response(
            response:409,
            description:"License already used"
        )

    ]

)]







#[OA\Get(
    path:"/admin/licenses",
    tags:["License System"],
    summary:"Get licenses",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    responses:[


        new OA\Response(
            response:200,
            description:"License list"
        )

    ]

)]







#[OA\Post(
    path:"/admin/licenses/generate",
    tags:["License System"],
    summary:"Generate license",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    requestBody:new OA\RequestBody(

        required:true,

        content:new OA\JsonContent(

            properties:[


                new OA\Property(
                    property:"quantity",
                    type:"integer",
                    example:10
                ),


                new OA\Property(
                    property:"plan_id",
                    type:"integer",
                    example:1
                )


            ]

        )

    ),



    responses:[


        new OA\Response(
            response:201,
            description:"License generated"
        )


    ]

)]







class LicenseApi
{

}