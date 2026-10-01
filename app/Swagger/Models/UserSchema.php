<?php

namespace App\Swagger\Models;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema:"User",
    type:"object",

    properties:[

        new OA\Property(
            property:"id",
            type:"integer",
            example:2
        ),

        new OA\Property(
            property:"name",
            type:"string",
            example:"Test User"
        ),

        new OA\Property(
            property:"email",
            type:"string",
            example:"user@test.com"
        ),

        new OA\Property(
            property:"phone",
            type:"string",
            example:"01700000001"
        ),

        new OA\Property(
            property:"status",
            type:"string",
            example:"active"
        ),

        new OA\Property(
            property:"created_at",
            type:"string",
            format:"date-time",
            example:"2026-09-30T10:00:00Z"
        ),

        new OA\Property(
            property:"updated_at",
            type:"string",
            format:"date-time",
            example:"2026-09-30T10:00:00Z"
        )

    ]
)]
class UserSchema
{
}