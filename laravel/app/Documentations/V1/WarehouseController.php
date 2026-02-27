<?php

namespace App\Documentations\V1;

use OpenApi\Attributes as OA;

class WarehouseController
{
    #[OA\Get(
        path: '/warehouses',
        summary: 'List all warehouses (paginated)',
        security: [['bearerAuth' => []]],
        tags: ['Warehouses'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated list of warehouses',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/WarehouseResource')),
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
        path: '/warehouses',
        summary: 'Create a new warehouse',
        security: [['bearerAuth' => []]],
        tags: ['Warehouses'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'location'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Main Warehouse'),
                    new OA\Property(property: 'location', type: 'string', maxLength: 255, example: 'New York, USA'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Warehouse created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/WarehouseResource'),
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
        path: '/warehouses/{warehouse}',
        summary: 'Get a single warehouse',
        security: [['bearerAuth' => []]],
        tags: ['Warehouses'],
        parameters: [
            new OA\Parameter(name: 'warehouse', in: 'path', required: true, description: 'Warehouse ID', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Warehouse details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/WarehouseResource'),
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
        path: '/warehouses/{warehouse}',
        summary: 'Update a warehouse',
        security: [['bearerAuth' => []]],
        tags: ['Warehouses'],
        parameters: [
            new OA\Parameter(name: 'warehouse', in: 'path', required: true, description: 'Warehouse ID', schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Updated Warehouse'),
                    new OA\Property(property: 'location', type: 'string', maxLength: 255, example: 'Los Angeles, USA'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Warehouse updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/WarehouseResource'),
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
        path: '/warehouses/{warehouse}',
        summary: 'Delete a warehouse',
        security: [['bearerAuth' => []]],
        tags: ['Warehouses'],
        parameters: [
            new OA\Parameter(name: 'warehouse', in: 'path', required: true, description: 'Warehouse ID', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Warehouse deleted',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Warehouse deleted successfully.'),
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

    #[OA\Get(
        path: '/warehouses/{warehouse}/inventory',
        summary: 'Get paginated stock inventory for a warehouse',
        description: 'Returns a paginated list of stock items in the specified warehouse. Results are cached with automatic invalidation on stock transfers.',
        security: [['bearerAuth' => []]],
        tags: ['Warehouses'],
        parameters: [
            new OA\Parameter(name: 'warehouse', in: 'path', required: true, description: 'Warehouse ID', schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, description: 'Items per page (default: 15)', schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'page', in: 'query', required: false, description: 'Page number (default: 1)', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated list of stock items in the warehouse',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/StockResource')),
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
            new OA\Response(
                response: 404,
                description: 'Warehouse not found',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
        ]
    )]
    public function inventory() {}
}
