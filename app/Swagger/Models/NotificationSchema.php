<?php

namespace App\Swagger\Models;

use OpenApi\Attributes as OA;


#[OA\Schema(
    schema:"Notification",
    type:"object"
)]
class NotificationSchema
{


    #[OA\Property(
        property:"id",
        type:"integer",
        example:1
    )]
    public int $id;



    #[OA\Property(
        property:"user_id",
        type:"integer",
        example:1
    )]
    public int $user_id;



    #[OA\Property(
        property:"title",
        type:"string",
        example:"Protection Alert"
    )]
    public string $title;



    #[OA\Property(
        property:"message",
        type:"string",
        example:"Suspicious activity detected"
    )]
    public string $message;



    #[OA\Property(
        property:"read_at",
        type:"string",
        format:"date-time",
        nullable:true,
        example:null
    )]
    public ?string $read_at;



    #[OA\Property(
        property:"created_at",
        type:"string",
        format:"date-time"
    )]
    public string $created_at;



    #[OA\Property(
        property:"updated_at",
        type:"string",
        format:"date-time"
    )]
    public string $updated_at;


}