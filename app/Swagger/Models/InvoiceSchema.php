<?php

namespace App\Swagger\Models;

use OpenApi\Attributes as OA;


#[OA\Schema(
    schema:"Invoice",
    type:"object"
)]
class InvoiceSchema
{


    #[OA\Property(
        property:"id",
        type:"integer",
        example:1
    )]
    public int $id;



    #[OA\Property(
        property:"invoice_no",
        type:"string",
        example:"INV-20260930-A8F92"
    )]
    public string $invoice_no;



    #[OA\Property(
        property:"user_id",
        type:"integer",
        example:1
    )]
    public int $user_id;



    #[OA\Property(
        property:"amount",
        type:"number",
        format:"float",
        example:10.00
    )]
    public float $amount;



    #[OA\Property(
        property:"currency",
        type:"string",
        example:"USD"
    )]
    public string $currency;



    #[OA\Property(
        property:"status",
        type:"string",
        example:"paid"
    )]
    public string $status;



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