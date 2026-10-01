<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


/*
|--------------------------------------------------------------------------
| Notification System API Documentation
|--------------------------------------------------------------------------
*/


#[OA\Get(
    path: "/devices/{id}/protection/notifications",

    tags: ["Notification System"],

    summary: "Get protection notifications",

    security: [
        [
            "bearerAuth" => []
        ]
    ],

    parameters: [

        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,

            schema: new OA\Schema(
                type: "integer",
                example: 1
            )
        )

    ],

    responses: [

        new OA\Response(
            response: 200,
            description: "Notification list",

            content: new OA\JsonContent(
                type: "array",

                items: new OA\Items(
                    ref: "#/components/schemas/Notification"
                )
            )
        ),

        new OA\Response(
            response: 401,
            description: "Unauthenticated",

            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Unauthenticated."
                    )
                ]
            )
        ),

        new OA\Response(
            response: 404,
            description: "Device not found",

            content: new OA\JsonContent(
                ref: "#/components/schemas/ErrorResponse"
            )
        )

    ]

)]


#[OA\Put(
    path: "/devices/{device}/protection/notifications/{id}/read",

    tags: ["Notification System"],

    summary: "Mark protection notification as read",

    security: [
        [
            "bearerAuth" => []
        ]
    ],

    parameters: [

        new OA\Parameter(
            name: "device",
            in: "path",
            required: true,

            schema: new OA\Schema(
                type: "integer",
                example: 3
            )
        ),

        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,

            schema: new OA\Schema(
                type: "integer",
                example: 1
            )
        )

    ],

    responses: [

        new OA\Response(
            response: 200,
            description: "Notification marked as read",

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
                        example: "Notification marked as read."
                    ),

                    new OA\Property(
                        property: "data",
                        type: "object",

                        properties: [

                            new OA\Property(
                                property: "id",
                                type: "integer",
                                example: 1
                            ),

                            new OA\Property(
                                property: "read_at",
                                type: "string",
                                format: "date-time",
                                example: "2026-10-01T06:34:45.000000Z"
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
                        property: "message",
                        type: "string",
                        example: "Unauthenticated."
                    )
                ]
            )
        ),

        new OA\Response(
            response: 404,
            description: "Notification not found",

            content: new OA\JsonContent(
                ref: "#/components/schemas/ErrorResponse"
            )
        )

    ]

)]


#[OA\Get(
    path: "/admin/notifications",

    tags: ["Notification System"],

    summary: "Admin notifications",

    security: [
        [
            "bearerAuth" => []
        ]
    ],

    responses: [

        new OA\Response(
            response: 200,
            description: "Admin notification list",

            content: new OA\JsonContent(
                type: "array",

                items: new OA\Items(
                    ref: "#/components/schemas/Notification"
                )
            )
        ),

        new OA\Response(
            response: 401,
            description: "Unauthenticated",

            content: new OA\JsonContent(
                properties: [
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


#[OA\Get(
    path: "/admin/notifications/unread",

    tags: ["Notification System"],

    summary: "Unread admin notifications",

    security: [
        [
            "bearerAuth" => []
        ]
    ],

    responses: [

        new OA\Response(
            response: 200,
            description: "Unread notifications",

            content: new OA\JsonContent(
                type: "array",

                items: new OA\Items(
                    ref: "#/components/schemas/Notification"
                )
            )
        ),

        new OA\Response(
            response: 401,
            description: "Unauthenticated",

            content: new OA\JsonContent(
                properties: [
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


#[OA\Put(
    path: "/admin/notifications/{id}/read",

    tags: ["Notification System"],

    summary: "Mark admin notification read",

    security: [
        [
            "bearerAuth" => []
        ]
    ],

    parameters: [

        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,

            schema: new OA\Schema(
                type: "integer",
                example: 1
            )
        )

    ],

    responses: [

        new OA\Response(
            response: 200,
            description: "Notification read",

            content: new OA\JsonContent(
                ref: "#/components/schemas/Notification"
            )
        ),

        new OA\Response(
            response: 401,
            description: "Unauthenticated",

            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: "message",
                        type: "string",
                        example: "Unauthenticated."
                    )
                ]
            )
        ),

        new OA\Response(
            response: 404,
            description: "Notification not found",

            content: new OA\JsonContent(
                ref: "#/components/schemas/ErrorResponse"
            )
        )

    ]

)]


class NotificationApi
{
}