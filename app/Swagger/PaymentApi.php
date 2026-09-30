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



    responses:[


        new OA\Response(
            response:201,

            description:"Payment created",

            content:new OA\JsonContent(
                ref:"#/components/schemas/Payment"
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
            response:422,

            description:"Payment confirmation failed",

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

            description:"Webhook processed",

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
                        example:"Webhook processed successfully."
                    )

                ]

            )
        ),



        new OA\Response(
            response:401,

            description:"Invalid signature",

            content:new OA\JsonContent(
                ref:"#/components/schemas/ErrorResponse"
            )
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

            description:"Payment history",

            content:new OA\JsonContent(

                type:"array",

                items:new OA\Items(
                    ref:"#/components/schemas/Payment"
                )

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
                    ref:"#/components/schemas/Payment"
                )

            )
        )

    ]

)]





class PaymentApi
{

}