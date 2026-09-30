<?php

namespace App\Swagger\Models;

use OpenApi\Attributes as OA;


#[OA\Schema(
    schema:"ApiResponse",
    type:"object"
)]
class ApiResponseSchema
{


    #[OA\Property(
        property:"success",
        type:"boolean",
        example:true
    )]
    public bool $success;



    #[OA\Property(
        property:"message",
        type:"string",
        example:"Operation completed successfully"
    )]
    public string $message;



    #[OA\Property(
        property:"data",
        type:"object",
        nullable:true
    )]
    public ?object $data;



}