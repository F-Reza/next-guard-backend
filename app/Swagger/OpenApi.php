<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


#[OA\Info(
    version: "1.0.0",
    title: "Next Guard API",
    description: "Next Guard Backend API Documentation"
)]

#[OA\Server(
    url: "/api/v1",
    description: "Next Guard API Server"
)]

#[OA\Get(
    path: "/health",
    tags: ["Health"],
    summary: "API Health Check",
    responses: [
        new OA\Response(
            response: 200,
            description: "API is running"
        )
    ]
)]

#[OA\SecurityScheme(
    securityScheme:"bearerAuth",
    type:"http",
    scheme:"bearer",
    bearerFormat:"JWT"
)]

class OpenApi
{

}