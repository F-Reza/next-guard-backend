<?php

namespace App\Swagger\Models;

use OpenApi\Attributes as OA;


#[OA\Schema(
    schema:"ErrorResponse",
    type:"object"
)]
class ErrorResponseSchema
{


    #[OA\Property(
        property:"success",
        type:"boolean",
        example:false
    )]
    public bool $success;



    #[OA\Property(
        property:"message",
        type:"string",
        example:"Validation error"
    )]
    public string $message;



    #[OA\Property(
        property:"errors",
        type:"object",
        nullable:true
    )]
    public ?object $errors;


}