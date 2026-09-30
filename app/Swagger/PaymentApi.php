<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/*
|--------------------------------------------------------------------------
| Payment System API Documentation
|--------------------------------------------------------------------------
*/



#[OA\Post(
    path:"/payments/create",

    tags:["Payment System"],

    summary:"Create payment",


    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    requestBody:new OA\RequestBody(

        required:true,


        content:new OA\JsonContent(

            required:[

                "subscription_id",
                "gateway"

            ],


            properties:[


                new OA\Property(

                    property:"subscription_id",

                    type:"integer",

                    example:1

                ),



                new OA\Property(

                    property:"gateway",

                    type:"string",

                    example:"stripe"

                )

            ]

        )

    ),



    responses:[


        new OA\Response(

            response:201,

            description:"Payment created"

        ),


        new OA\Response(

            response:422,

            description:"Validation error"

        )

    ]

)]









#[OA\Post(
    path:"/payments/confirm",

    tags:["Payment System"],

    summary:"Confirm payment",


    security:[
        [
            "bearerAuth"=>[]
        ]
    ],



    requestBody:new OA\RequestBody(

        required:true,


        content:new OA\JsonContent(

            required:[

                "transaction_id"

            ],


            properties:[


                new OA\Property(

                    property:"transaction_id",

                    type:"string",

                    example:"STRIPE-TEST123"

                )

            ]

        )

    ),



    responses:[


        new OA\Response(

            response:200,

            description:"Payment confirmed"

        ),


        new OA\Response(

            response:404,

            description:"Payment not found"

        )

    ]

)]









#[OA\Post(
    path:"/payments/webhook",

    tags:["Payment System"],

    summary:"Payment gateway webhook",


    parameters:[


        new OA\Parameter(

            name:"X-Webhook-Signature",

            in:"header",

            required:true,


            schema:new OA\Schema(

                type:"string",

                example:"a83f9d82..."

            )

        )


    ],



    requestBody:new OA\RequestBody(

        required:true,


        content:new OA\JsonContent(

            required:[

                "gateway",
                "event_id",
                "transaction_id"

            ],



            properties:[


                new OA\Property(

                    property:"gateway",

                    type:"string",

                    example:"stripe"

                ),



                new OA\Property(

                    property:"event_id",

                    type:"string",

                    example:"evt_test_001"

                ),



                new OA\Property(

                    property:"transaction_id",

                    type:"string",

                    example:"STRIPE-TEST123"

                )

            ]

        )

    ),



    responses:[


        new OA\Response(

            response:200,

            description:"Webhook processed"

        ),


        new OA\Response(

            response:401,

            description:"Invalid webhook signature"

        )

    ]

)]









#[OA\Get(
    path:"/payments",

    tags:["Payment System"],

    summary:"Get payment history",


    security:[
        [
            "bearerAuth"=>[]
        ]
    ],



    responses:[


        new OA\Response(

            response:200,

            description:"Payment history"

        )

    ]

)]









#[OA\Get(
    path:"/invoices",

    tags:["Payment System"],

    summary:"Get invoices",


    security:[
        [
            "bearerAuth"=>[]
        ]
    ],



    responses:[


        new OA\Response(

            response:200,

            description:"Invoice list"

        )

    ]

)]








class PaymentApi
{

}