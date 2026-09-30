<?php

namespace App\Swagger\Models;

use OpenApi\Attributes as OA;


#[OA\Schema(
    schema:"User",
    type:"object"
)]
class UserSchema
{


    #[OA\Property(
        property:"id",
        type:"integer",
        example:1
    )]
    public int $id;



    #[OA\Property(
        property:"name",
        type:"string",
        example:"John Doe"
    )]
    public string $name;



    #[OA\Property(
        property:"email",
        type:"string",
        example:"user@test.com"
    )]
    public string $email;



    #[OA\Property(
        property:"created_at",
        type:"string",
        format:"date-time",
        example:"2026-09-30T10:00:00Z"
    )]
    public string $created_at;



    #[OA\Property(
        property:"updated_at",
        type:"string",
        format:"date-time",
        example:"2026-09-30T10:00:00Z"
    )]
    public string $updated_at;


}