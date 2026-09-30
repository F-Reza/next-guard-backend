<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/*
|--------------------------------------------------------------------------
| Subscription System API Documentation
|--------------------------------------------------------------------------
*/



#[OA\Get(
    path:"/subscription-plans",
    tags:["Subscription System"],
    summary:"Get subscription plans",

    responses:[

        new OA\Response(
            response:200,
            description:"Subscription plans list"
        )

    ]

)]





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
            description:"User subscription list"
        ),

        new OA\Response(
            response:401,
            description:"Unauthenticated"
        )

    ]

)]






#[OA\Post(
    path:"/subscriptions/activate",
    tags:["Subscription System"],
    summary:"Activate subscription",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    requestBody:new OA\RequestBody(

        required:true,

        content:new OA\JsonContent(

            required:[
                "subscription_id"
            ],

            properties:[

                new OA\Property(
                    property:"subscription_id",
                    type:"integer",
                    example:1
                )

            ]

        )

    ),



    responses:[

        new OA\Response(
            response:200,
            description:"Subscription activated"
        ),

        new OA\Response(
            response:422,
            description:"Validation error"
        )

    ]

)]






#[OA\Get(
    path:"/subscriptions/current",
    tags:["Subscription System"],
    summary:"Get current subscription",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    responses:[

        new OA\Response(
            response:200,
            description:"Current subscription"
        )

    ]

)]







#[OA\Post(
    path:"/subscriptions/change-plan",
    tags:["Subscription System"],
    summary:"Change subscription plan",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    requestBody:new OA\RequestBody(

        required:true,

        content:new OA\JsonContent(

            required:[
                "plan_id"
            ],

            properties:[


                new OA\Property(
                    property:"plan_id",
                    type:"integer",
                    example:2
                )


            ]

        )

    ),



    responses:[

        new OA\Response(
            response:200,
            description:"Plan changed successfully"
        )

    ]

)]







#[OA\Get(
    path:"/subscriptions/history",
    tags:["Subscription System"],
    summary:"Get subscription history",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    responses:[

        new OA\Response(
            response:200,
            description:"Subscription history"
        )

    ]

)]







#[OA\Get(
    path:"/subscription/status",
    tags:["Subscription System"],
    summary:"Get subscription status",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    responses:[

        new OA\Response(
            response:200,
            description:"Subscription status"
        )

    ]

)]





class SubscriptionApi
{

}