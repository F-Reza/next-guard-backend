<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/*
|--------------------------------------------------------------------------
| Subscription System API Documentation
|--------------------------------------------------------------------------
*/


#[OA\Get(
    path:"/subscriptions",

    tags:["Subscription System"],

    summary:"Get user subscriptions",


    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    responses:[

        new OA\Response(
            response:200,

            description:"Subscription list",

            content:new OA\JsonContent(

                type:"array",

                items:new OA\Items(
                    ref:"#/components/schemas/Subscription"
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






#[OA\Post(
    path:"/subscriptions/create",

    tags:["Subscription System"],

    summary:"Create subscription",


    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    requestBody:new OA\RequestBody(

        required:true,


        content:new OA\JsonContent(

            required:[

                "subscription_plan_id",
                "device_id"

            ],


            properties:[


                new OA\Property(
                    property:"subscription_plan_id",
                    type:"integer",
                    example:1
                ),


                new OA\Property(
                    property:"device_id",
                    type:"integer",
                    example:5
                )


            ]

        )

    ),


    responses:[


        new OA\Response(
            response:201,

            description:"Subscription created",

            content:new OA\JsonContent(
                ref:"#/components/schemas/Subscription"
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








#[OA\Get(
    path:"/subscriptions/{id}",

    tags:["Subscription System"],

    summary:"Get subscription details",


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

            description:"Subscription details",

            content:new OA\JsonContent(
                ref:"#/components/schemas/Subscription"
            )

        ),


        new OA\Response(

            response:404,

            description:"Subscription not found"

        )

    ]

)]









#[OA\Post(
    path:"/subscriptions/{id}/cancel",

    tags:["Subscription System"],

    summary:"Cancel subscription",


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

            description:"Subscription cancelled",

            content:new OA\JsonContent(
                ref:"#/components/schemas/Subscription"
            )

        )


    ]

)]







class SubscriptionApi
{

}