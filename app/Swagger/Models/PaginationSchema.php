<?php

namespace App\Swagger\Models;

use OpenApi\Attributes as OA;


#[OA\Schema(
    schema:"Pagination",
    type:"object"
)]
class PaginationSchema
{


    #[OA\Property(
        property:"current_page",
        type:"integer",
        example:1
    )]
    public int $current_page;



    #[OA\Property(
        property:"per_page",
        type:"integer",
        example:15
    )]
    public int $per_page;



    #[OA\Property(
        property:"total",
        type:"integer",
        example:100
    )]
    public int $total;


}