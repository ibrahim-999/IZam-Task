<?php

namespace App\Documentations\V1;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Izam Inventory API',
    description: 'API documentation for the Izam Inventory Management System',
    contact: new OA\Contact(
        name: 'Izam Support',
        email: 'admin@izam.co'
    )
)]
#[OA\Server(
    url: '/api/v1',
    description: 'API V1'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Sanctum'
)]
#[OA\Tag(name: 'Auth', description: 'Authentication endpoints')]
#[OA\Tag(name: 'Inventory', description: 'Inventory item management')]
#[OA\Tag(name: 'Warehouses', description: 'Warehouse management')]
#[OA\Tag(name: 'Stock Transfers', description: 'Stock transfer operations')]
#[OA\Schema(
    schema: 'UserResource',
    required: ['id', 'name', 'email', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
        new OA\Property(property: 'email_verified_at', type: 'string', format: 'date-time', nullable: true, example: '2026-01-01T00:00:00.000000Z'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00.000000Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00.000000Z'),
    ]
)]
#[OA\Schema(
    schema: 'InventoryItemResource',
    required: ['id', 'name', 'sku', 'price', 'category', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Wireless Mouse'),
        new OA\Property(property: 'sku', type: 'string', example: 'SKU-1234-AB'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'A high-quality wireless mouse'),
        new OA\Property(property: 'price', type: 'string', example: '29.99'),
        new OA\Property(property: 'category', type: 'string', example: 'Electronics'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00.000000Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00.000000Z'),
    ]
)]
#[OA\Schema(
    schema: 'WarehouseResource',
    required: ['id', 'name', 'location', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Main Warehouse'),
        new OA\Property(property: 'location', type: 'string', example: 'New York, USA'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00.000000Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00.000000Z'),
    ]
)]
#[OA\Schema(
    schema: 'StockResource',
    required: ['id', 'warehouse_id', 'inventory_item_id', 'quantity', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'warehouse_id', type: 'integer', example: 1),
        new OA\Property(property: 'inventory_item_id', type: 'integer', example: 1),
        new OA\Property(property: 'quantity', type: 'integer', example: 50),
        new OA\Property(property: 'inventory_item', ref: '#/components/schemas/InventoryItemResource'),
        new OA\Property(property: 'warehouse', ref: '#/components/schemas/WarehouseResource'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00.000000Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00.000000Z'),
    ]
)]
#[OA\Schema(
    schema: 'StockTransferResource',
    required: ['id', 'user_id', 'quantity', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'from_warehouse', ref: '#/components/schemas/WarehouseResource'),
        new OA\Property(property: 'to_warehouse', ref: '#/components/schemas/WarehouseResource'),
        new OA\Property(property: 'inventory_item', ref: '#/components/schemas/InventoryItemResource'),
        new OA\Property(property: 'user_id', type: 'integer', example: 1),
        new OA\Property(property: 'quantity', type: 'integer', example: 20),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00.000000Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00.000000Z'),
    ]
)]
#[OA\Schema(
    schema: 'ValidationError',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
        new OA\Property(property: 'errors', type: 'object',
            additionalProperties: new OA\AdditionalProperties(
                type: 'array',
                items: new OA\Items(type: 'string')
            )
        ),
    ]
)]
#[OA\Schema(
    schema: 'MessageResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string'),
    ]
)]
#[OA\Schema(
    schema: 'ErrorResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string'),
        new OA\Property(property: 'error', type: 'string'),
    ]
)]
#[OA\Schema(
    schema: 'PaginationMeta',
    properties: [
        new OA\Property(property: 'current_page', type: 'integer', example: 1),
        new OA\Property(property: 'from', type: 'integer', example: 1),
        new OA\Property(property: 'last_page', type: 'integer', example: 5),
        new OA\Property(property: 'per_page', type: 'integer', example: 15),
        new OA\Property(property: 'to', type: 'integer', example: 15),
        new OA\Property(property: 'total', type: 'integer', example: 75),
    ]
)]
#[OA\Schema(
    schema: 'PaginationLinks',
    properties: [
        new OA\Property(property: 'first', type: 'string', nullable: true),
        new OA\Property(property: 'last', type: 'string', nullable: true),
        new OA\Property(property: 'prev', type: 'string', nullable: true),
        new OA\Property(property: 'next', type: 'string', nullable: true),
    ]
)]
class OpenApi {}
