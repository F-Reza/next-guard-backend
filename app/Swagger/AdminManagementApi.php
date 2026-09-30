<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/*
|--------------------------------------------------------------------------
| Admin Management API Documentation
|--------------------------------------------------------------------------
*/



#[OA\Get(
    path:"/admin/admins",
    tags:["Admin Management"],
    summary:"Get admin list",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    responses:[

        new OA\Response(
            response:200,
            description:"Admin list"
        )

    ]

)]






#[OA\Post(
    path:"/admin/admins",
    tags:["Admin Management"],
    summary:"Create admin",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    requestBody:new OA\RequestBody(

        required:true,

        content:new OA\JsonContent(

            required:[
                "name",
                "email",
                "password"
            ],

            properties:[


                new OA\Property(
                    property:"name",
                    type:"string",
                    example:"Admin User"
                ),


                new OA\Property(
                    property:"email",
                    type:"string",
                    example:"admin@test.com"
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
            response:201,
            description:"Admin created"
        )

    ]

)]







#[OA\Get(
    path:"/admin/admins/{id}",
    tags:["Admin Management"],
    summary:"Get admin details",

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
            description:"Admin details"
        )

    ]

)]







#[OA\Put(
    path:"/admin/admins/{id}",
    tags:["Admin Management"],
    summary:"Update admin",

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
            description:"Admin updated"
        )

    ]

)]







#[OA\Delete(
    path:"/admin/admins/{id}",
    tags:["Admin Management"],
    summary:"Delete admin",

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
            description:"Admin deleted"
        )

    ]

)]







#[OA\Post(
    path:"/admin/admins/{id}/permissions",
    tags:["Admin Management"],
    summary:"Assign permission to admin",

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
            description:"Permission assigned"
        )

    ]

)]







#[OA\Delete(
    path:"/admin/admins/{id}/permissions/{permission}",
    tags:["Admin Management"],
    summary:"Remove admin permission",

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

        ),


        new OA\Parameter(
            name:"permission",
            in:"path",
            required:true,

            schema:new OA\Schema(
                type:"string",
                example:"manage_users"
            )

        )


    ],


    responses:[

        new OA\Response(
            response:200,
            description:"Permission removed"
        )

    ]

)]







#[OA\Get(
    path:"/admin/permissions",
    tags:["Admin Management"],
    summary:"Get permissions",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    responses:[

        new OA\Response(
            response:200,
            description:"Permission list"
        )

    ]

)]








#[OA\Get(
    path:"/admin/plans",
    tags:["Admin Management"],
    summary:"Get subscription plans",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    responses:[

        new OA\Response(
            response:200,
            description:"Plans list"
        )

    ]

)]








#[OA\Post(
    path:"/admin/plans",
    tags:["Admin Management"],
    summary:"Create subscription plan",

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
                    property:"name",
                    type:"string",
                    example:"Premium"
                ),


                new OA\Property(
                    property:"price",
                    type:"number",
                    example:9.99
                )


            ]

        )

    ),


    responses:[

        new OA\Response(
            response:201,
            description:"Plan created"
        )

    ]

)]








#[OA\Get(
    path:"/admin/subscriptions",
    tags:["Admin Management"],
    summary:"Get all subscriptions",

    security:[
        [
            "bearerAuth"=>[]
        ]
    ],


    responses:[

        new OA\Response(
            response:200,
            description:"Subscription list"
        )

    ]

)]








#[OA\Post(
    path:"/admin/subscriptions/{id}/cancel",
    tags:["Admin Management"],
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
            description:"Subscription cancelled"
        )

    ]

)]







class AdminManagementApi
{

}