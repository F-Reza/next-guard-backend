<?php

namespace App\Swagger\Models;

use OpenApi\Attributes as OA;


#[OA\Schema(
    schema:"Notification",

    type:"object",

    properties:[


        new OA\Property(
            property:"id",
            type:"integer",
            example:1
        ),


        new OA\Property(
            property:"user_id",
            type:"integer",
            example:1
        ),


        new OA\Property(
            property:"title",
            type:"string",
            example:"Protection alert"
        ),


        new OA\Property(
            property:"message",
            type:"string",
            example:"Suspicious activity detected"
        ),


        new OA\Property(
            property:"read_at",
            type:"string",
            nullable:true
        ),


        new OA\Property(
            property:"created_at",
            type:"string"
        )


    ]

)]


class NotificationSchema
{

}