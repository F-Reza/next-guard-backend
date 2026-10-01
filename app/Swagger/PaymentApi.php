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
                "amount",
                "currency",
                "gateway"
            ],

            properties:[


                new OA\Property(
                    property:"amount",
                    type:"number",
                    example:10
                ),


                new OA\Property(
                    property:"currency",
                    type:"string",
                    example:"USD"
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

            description:"Payment created",

            content:new OA\JsonContent(
                ref:"#/components/schemas/Payment"
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

            description:"Payment confirmed",

            content:new OA\JsonContent(
                ref:"#/components/schemas/Payment"
            )

        ),


        new OA\Response(

            response:404,

            description:"Payment not found",

            content:new OA\JsonContent(
                ref:"#/components/schemas/ErrorResponse"
            )

        )

    ]

)]






#[OA\Post(
    path:"/payments/webhook",

    tags:["Payment System"],

    summary:"Payment gateway webhook",


    responses:[


        new OA\Response(

            response:200,

            description:"Webhook received"

        )

    ]

)]







#[OA\Get(
    path:"/payments",

    tags:["Payment System"],

    summary:"Get payments",
    // summary:"Get payment history",


    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    responses:[


        new OA\Response(

            response:200,

            description:"Payment list",

            content:new OA\JsonContent(

                type:"array",

                items:new OA\Items(

                    ref:"#/components/schemas/Payment"

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

            description:"Invoice list",

            content:new OA\JsonContent(

                type:"array",

                items:new OA\Items(

                    ref:"#/components/schemas/Invoice"

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





class PaymentApi
{

}