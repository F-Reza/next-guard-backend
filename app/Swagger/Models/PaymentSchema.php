<?php

namespace App\Swagger\Models;

use OpenApi\Attributes as OA;


#[OA\Schema(
    schema:"Payment",
    type:"object"
)]
class PaymentSchema
{


    #[OA\Property(
        property:"id",
        type:"integer",
        example:1
    )]
    public int $id;



    #[OA\Property(
        property:"transaction_id",
        type:"string",
        example:"STRIPE-TEST123"
    )]
    public string $transaction_id;



    #[OA\Property(
        property:"gateway",
        type:"string",
        example:"stripe"
    )]
    public string $gateway;



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
        property:"paid_at",
        type:"string",
        format:"date-time",
        nullable:true,
        example:"2026-09-30T12:00:00Z"
    )]
    public ?string $paid_at;


}