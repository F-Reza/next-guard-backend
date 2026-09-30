<?php

namespace App\Swagger\Models;

use OpenApi\Attributes as OA;


#[OA\Schema(
    schema:"Device",
    type:"object"
)]
class DeviceSchema
{


    #[OA\Property(
        property:"id",
        type:"integer",
        example:1
    )]
    public int $id;



    #[OA\Property(
        property:"device_uuid_hash",
        type:"string",
        example:"a8f7c91d83..."
    )]
    public string $device_uuid_hash;



    #[OA\Property(
        property:"platform",
        type:"string",
        example:"android"
    )]
    public string $platform;



    #[OA\Property(
        property:"status",
        type:"string",
        example:"active"
    )]
    public string $status;



    #[OA\Property(
        property:"user_id",
        type:"integer",
        example:1
    )]
    public int $user_id;



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