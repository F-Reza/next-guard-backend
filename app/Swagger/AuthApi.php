<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/*
|--------------------------------------------------------------------------
| Authentication API Documentation
|--------------------------------------------------------------------------
|
| Routes:
|
| POST /auth/register
| POST /auth/login
| POST /auth/refresh
| GET  /auth/me
| POST /auth/logout
|
*/


/*
|--------------------------------------------------------------------------
| User Registration
|--------------------------------------------------------------------------
*/

#[OA\Post(
    path: "/auth/register",
    operationId: "userRegister",
    tags: ["Authentication"],
    summary: "User Registration",
    description: "Register a new user account.",

    security: [],

    requestBody: new OA\RequestBody(
        required: true,

        content: new OA\JsonContent(

            required: [
                "name",
                "password",
                "password_confirmation"
            ],

            properties: [

                new OA\Property(
                    property: "name",
                    type: "string",
                    maxLength: 120,
                    example: "John Doe"
                ),

                new OA\Property(
                    property: "email",
                    type: "string",
                    format: "email",
                    nullable: true,
                    example: "user@test.com"
                ),

                new OA\Property(
                    property: "phone",
                    type: "string",
                    nullable: true,
                    maxLength: 30,
                    example: "+8801712345678"
                ),

                new OA\Property(
                    property: "password",
                    type: "string",
                    format: "password",
                    minLength: 8,
                    example: "password123"
                ),

                new OA\Property(
                    property: "password_confirmation",
                    type: "string",
                    format: "password",
                    minLength: 8,
                    example: "password123"
                )

            ]
        )
    ),

    responses: [

        new OA\Response(
            response: 201,
            description: "Registration successful",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: true
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Registration successful."
                    ),

                    new OA\Property(
                        property: "data",
                        type: "object",

                        properties: [

                            new OA\Property(
                                property: "user",
                                type: "object",

                                properties: [

                                    new OA\Property(
                                        property: "id",
                                        type: "integer",
                                        example: 1
                                    ),

                                    new OA\Property(
                                        property: "name",
                                        type: "string",
                                        example: "John Doe"
                                    ),

                                    new OA\Property(
                                        property: "email",
                                        type: "string",
                                        format: "email",
                                        nullable: true,
                                        example: "user@test.com"
                                    ),

                                    new OA\Property(
                                        property: "phone",
                                        type: "string",
                                        nullable: true,
                                        example: "+8801712345678"
                                    ),

                                    new OA\Property(
                                        property: "status",
                                        type: "string",
                                        example: "active"
                                    )

                                ]
                            ),

                            new OA\Property(
                                property: "access_token",
                                type: "string",
                                example: "eyJ0eXAiOiJKV1QiLCJhbGc..."
                            ),

                            new OA\Property(
                                property: "token_type",
                                type: "string",
                                example: "Bearer"
                            )

                        ]
                    )

                ]
            )
        ),

        new OA\Response(
            response: 422,
            description: "Validation failed",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: false
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Validation failed."
                    ),

                    new OA\Property(
                        property: "errors",
                        type: "object"
                    )

                ]
            )
        )

    ]
)]


/*
|--------------------------------------------------------------------------
| User Login
|--------------------------------------------------------------------------
*/

#[OA\Post(
    path: "/auth/login",
    operationId: "userLogin",
    tags: ["Authentication"],
    summary: "User Login",
    description: "Login using email or phone and an enrolled device.",

    security: [],

    requestBody: new OA\RequestBody(
        required: true,

        content: new OA\JsonContent(

            required: [
                "login",
                "password",
                "device_id"
            ],

            properties: [

                new OA\Property(
                    property: "login",
                    type: "string",
                    description: "User email or phone number.",
                    example: "user@test.com"
                ),

                new OA\Property(
                    property: "password",
                    type: "string",
                    format: "password",
                    example: "password123"
                ),

                new OA\Property(
                    property: "device_id",
                    type: "integer",
                    example: 1
                )

            ]
        )
    ),

    responses: [

        new OA\Response(
            response: 200,
            description: "Login successful",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: true
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Login successful."
                    ),

                    new OA\Property(
                        property: "data",
                        type: "object",

                        properties: [

                            new OA\Property(
                                property: "user",
                                type: "object",

                                properties: [

                                    new OA\Property(
                                        property: "id",
                                        type: "integer",
                                        example: 1
                                    ),

                                    new OA\Property(
                                        property: "name",
                                        type: "string",
                                        example: "John Doe"
                                    ),

                                    new OA\Property(
                                        property: "email",
                                        type: "string",
                                        format: "email",
                                        nullable: true,
                                        example: "user@test.com"
                                    ),

                                    new OA\Property(
                                        property: "phone",
                                        type: "string",
                                        nullable: true,
                                        example: "+8801712345678"
                                    ),

                                    new OA\Property(
                                        property: "status",
                                        type: "string",
                                        example: "active"
                                    )

                                ]
                            ),

                            new OA\Property(
                                property: "device_id",
                                type: "integer",
                                example: 1
                            ),

                            new OA\Property(
                                property: "access_token",
                                type: "string",
                                example: "eyJ0eXAiOiJKV1QiLCJhbGc..."
                            ),

                            new OA\Property(
                                property: "refresh_token",
                                type: "string",
                                example: "opaque-refresh-token..."
                            ),

                            new OA\Property(
                                property: "token_type",
                                type: "string",
                                example: "Bearer"
                            ),

                            new OA\Property(
                                property: "refresh_expires_at",
                                type: "string",
                                format: "date-time",
                                example: "2026-10-31T09:00:00Z"
                            )

                        ]
                    )

                ]
            )
        ),

        new OA\Response(
            response: 401,
            description: "Invalid login credentials",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: false
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Invalid login credentials."
                    )

                ]
            )
        ),

        new OA\Response(
            response: 403,
            description: "Account or device is not active",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: false
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Device is not registered for this account."
                    )

                ]
            )
        ),

        new OA\Response(
            response: 422,
            description: "Validation failed",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: false
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Validation failed."
                    ),

                    new OA\Property(
                        property: "errors",
                        type: "object"
                    )

                ]
            )
        )

    ]
)]


/*
|--------------------------------------------------------------------------
| Refresh Access Token
|--------------------------------------------------------------------------
*/

#[OA\Post(
    path: "/auth/refresh",
    operationId: "refreshAccessToken",
    tags: ["Authentication"],
    summary: "Refresh access token",
    description: "Rotate the opaque refresh token and issue a new access token. This endpoint does not require an access JWT.",

    security: [],

    requestBody: new OA\RequestBody(
        required: true,

        content: new OA\JsonContent(

            required: [
                "refresh_token"
            ],

            properties: [

                new OA\Property(
                    property: "refresh_token",
                    type: "string",
                    example: "opaque-refresh-token..."
                )

            ]
        )
    ),

    responses: [

        new OA\Response(
            response: 200,
            description: "Token refreshed successfully",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: true
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Token refreshed successfully."
                    ),

                    new OA\Property(
                        property: "data",
                        type: "object",

                        properties: [

                            new OA\Property(
                                property: "access_token",
                                type: "string",
                                example: "eyJ0eXAiOiJKV1QiLCJhbGc..."
                            ),

                            new OA\Property(
                                property: "refresh_token",
                                type: "string",
                                example: "new-opaque-refresh-token..."
                            ),

                            new OA\Property(
                                property: "token_type",
                                type: "string",
                                example: "Bearer"
                            ),

                            new OA\Property(
                                property: "refresh_expires_at",
                                type: "string",
                                format: "date-time",
                                example: "2026-10-31T09:00:00Z"
                            ),

                            new OA\Property(
                                property: "device_id",
                                type: "integer",
                                example: 1
                            )

                        ]
                    )

                ]
            )
        ),

        new OA\Response(
            response: 401,
            description: "Invalid, inactive, or expired refresh session",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: false
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Invalid refresh token."
                    )

                ]
            )
        ),

        new OA\Response(
            response: 403,
            description: "User account or device is not active",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: false
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Device is no longer active."
                    )

                ]
            )
        ),

        new OA\Response(
            response: 422,
            description: "Validation failed",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: false
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Validation failed."
                    ),

                    new OA\Property(
                        property: "errors",
                        type: "object"
                    )

                ]
            )
        )

    ]
)]


/*
|--------------------------------------------------------------------------
| Authenticated User
|--------------------------------------------------------------------------
*/

#[OA\Get(
    path: "/auth/me",
    operationId: "getAuthenticatedUser",
    tags: ["Authentication"],
    summary: "Get authenticated user",

    security: [
        [
            "bearerAuth" => []
        ]
    ],

    responses: [

        new OA\Response(
            response: 200,
            description: "Authenticated user",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: true
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Authenticated user."
                    ),

                    new OA\Property(
                        property: "data",
                        type: "object",

                        properties: [

                            new OA\Property(
                                property: "user",
                                type: "object",

                                properties: [

                                    new OA\Property(
                                        property: "id",
                                        type: "integer",
                                        example: 1
                                    ),

                                    new OA\Property(
                                        property: "name",
                                        type: "string",
                                        example: "John Doe"
                                    ),

                                    new OA\Property(
                                        property: "email",
                                        type: "string",
                                        format: "email",
                                        nullable: true,
                                        example: "user@test.com"
                                    ),

                                    new OA\Property(
                                        property: "phone",
                                        type: "string",
                                        nullable: true,
                                        example: "+8801712345678"
                                    ),

                                    new OA\Property(
                                        property: "status",
                                        type: "string",
                                        example: "active"
                                    )

                                ]
                            )

                        ]
                    )

                ]
            )
        ),

        new OA\Response(
            response: 401,
            description: "Unauthenticated",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: false
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Unauthenticated."
                    )

                ]
            )
        )

    ]
)]


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

#[OA\Post(
    path: "/auth/logout",
    operationId: "userLogout",
    tags: ["Authentication"],
    summary: "Logout user",

    security: [
        [
            "bearerAuth" => []
        ]
    ],

    responses: [

        new OA\Response(
            response: 200,
            description: "Logout successful",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: true
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Logout successful."
                    )

                ]
            )
        ),

        new OA\Response(
            response: 401,
            description: "Unauthenticated",

            content: new OA\JsonContent(

                properties: [

                    new OA\Property(
                        property: "success",
                        type: "boolean",
                        example: false
                    ),

                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Unauthenticated."
                    )

                ]
            )
        )

    ]
)]


class AuthApi
{
}