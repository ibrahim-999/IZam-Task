<?php

namespace App\Documentations\V1;

use OpenApi\Attributes as OA;

class InventoryItemController
{
    #[OA\Get(
        path: '/inventory',
        summary: 'List all inventory items (paginated)',
        security: [['bearerAuth' => []]],
        tags: ['Inventory'],
        parameters: [
            new OA\Parameter(name: 'name', in: 'query', required: false, description: 'Filter by name (partial match)', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'category', in: 'query', required: false, description: 'Filter by exact category', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'price_min', in: 'query', required: false, description: 'Minimum price filter', schema: new OA\Schema(type: 'number')),
            new OA\Parameter(name: 'price_max', in: 'query', required: false, description: 'Maximum price filter', schema: new OA\Schema(type: 'number')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, description: 'Items per page (default: 15)', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated list of inventory items',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/InventoryItemResource')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/PaginationLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
        ]
    )]
    public function index() {}

    #[OA\Post(
        path: '/inventory',
        summary: 'Create a new inventory item',
        security: [['bearerAuth' => []]],
        tags: ['Inventory'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'sku', 'price', 'category'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Wireless Mouse'),
                    new OA\Property(property: 'sku', type: 'string', maxLength: 255, example: 'SKU-1234-AB'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'A high-quality wireless mouse'),
                    new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0, example: 29.99),
                    new OA\Property(property: 'category', type: 'string', maxLength: 255, example: 'Electronics'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Inventory item created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/InventoryItemResource'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
            new OA\Response(
                response: 500,
                description: 'Server error',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function store() {}

    #[OA\Get(
        path: '/inventory/{inventory}',
        summary: 'Get a single inventory item',
        security: [['bearerAuth' => []]],
        tags: ['Inventory'],
        parameters: [
            new OA\Parameter(name: 'inventory', in: 'path', required: true, description: 'Inventory item ID', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Inventory item details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/InventoryItemResource'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Not found',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
        ]
    )]
    public function show() {}

    #[OA\Put(
        path: '/inventory/{inventory}',
        summary: 'Update an inventory item',
        security: [['bearerAuth' => []]],
        tags: ['Inventory'],
        parameters: [
            new OA\Parameter(name: 'inventory', in: 'path', required: true, description: 'Inventory item ID', schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Updated Mouse'),
                    new OA\Property(property: 'sku', type: 'string', maxLength: 255, example: 'SKU-5678-CD'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Updated description'),
                    new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0, example: 39.99),
                    new OA\Property(property: 'category', type: 'string', maxLength: 255, example: 'Electronics'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Inventory item updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/InventoryItemResource'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Not found',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
            new OA\Response(
                response: 500,
                description: 'Server error',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: '/inventory/{inventory}',
        summary: 'Delete an inventory item',
        security: [['bearerAuth' => []]],
        tags: ['Inventory'],
        parameters: [
            new OA\Parameter(name: 'inventory', in: 'path', required: true, description: 'Inventory item ID', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Item deleted',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Item deleted successfully.'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Not found',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
            new OA\Response(
                response: 500,
                description: 'Server error',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function destroy() {}
}
