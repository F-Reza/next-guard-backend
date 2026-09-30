<?php

namespace App\Swagger\Models;

use OpenApi\Attributes as OA;


#[OA\Schema(
    schema:"Subscription",

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
            property:"device_id",
            type:"integer",
            example:1
        ),


        new OA\Property(
            property:"subscription_plan_id",
            type:"integer",
            example:1
        ),


        new OA\Property(
            property:"status",
            type:"string",
            example:"active"
        ),


        new OA\Property(
            property:"starts_at",
            type:"string",
            example:"2026-09-30"
        ),


        new OA\Property(
            property:"expires_at",
            type:"string",
            example:"2026-10-30"
        )

    ]

)]


class SubscriptionSchema
{

}