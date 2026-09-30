<?php

namespace App\Swagger\Models;

use OpenApi\Attributes as OA;


#[OA\Schema(
    schema:"Subscription",
    type:"object"
)]
class SubscriptionSchema
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
        property:"device_id",
        type:"integer",
        example:1
    )]
    public int $device_id;



    #[OA\Property(
        property:"subscription_plan_id",
        type:"integer",
        example:1
    )]
    public int $subscription_plan_id;



    #[OA\Property(
        property:"status",
        type:"string",
        example:"active"
    )]
    public string $status;



    #[OA\Property(
        property:"starts_at",
        type:"string",
        format:"date-time",
        example:"2026-09-30T10:00:00Z"
    )]
    public string $starts_at;



    #[OA\Property(
        property:"expires_at",
        type:"string",
        format:"date-time",
        example:"2026-10-30T10:00:00Z"
    )]
    public string $expires_at;



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