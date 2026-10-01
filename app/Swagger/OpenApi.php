<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Next Guard API",
    description: "Next Guard Backend API Documentation"
)]

#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
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

class OpenApi
{
}