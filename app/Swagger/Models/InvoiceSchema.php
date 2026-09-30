<?php

namespace App\Swagger\Models;

use OpenApi\Attributes as OA;


#[OA\Schema(
    schema:"Invoice",

    type:"object",

    properties:[


        new OA\Property(
            property:"id",
            type:"integer",
            example:1
        ),


        new OA\Property(
            property:"invoice_no",
            type:"string",
            example:"INV-20260930-A8F92"
        ),


        new OA\Property(
            property:"amount",
            type:"number",
            example:10
        ),


        new OA\Property(
            property:"currency",
            type:"string",
            example:"USD"
        ),


        new OA\Property(
            property:"status",
            type:"string",
            example:"paid"
        )


    ]

)]


class InvoiceSchema
{

}