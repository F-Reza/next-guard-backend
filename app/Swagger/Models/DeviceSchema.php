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
        example:"a83bd92..."
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


}