<?php

namespace App\Documentations\V1;

use OpenApi\Attributes as OA;

class StockTransferController
{
    #[OA\Get(
        path: '/stock-transfers',
        summary: 'List all stock transfers (paginated)',
        security: [['bearerAuth' => []]],
        tags: ['Stock Transfers'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated list of stock transfers',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/StockTransferResource')),
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
        path: '/stock-transfers',
        summary: 'Create a new stock transfer',
        security: [['bearerAuth' => []]],
        tags: ['Stock Transfers'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['from_warehouse_id', 'to_warehouse_id', 'inventory_item_id', 'quantity'],
                properties: [
                    new OA\Property(property: 'from_warehouse_id', type: 'integer', example: 1),
                    new OA\Property(property: 'to_warehouse_id', type: 'integer', example: 2),
                    new OA\Property(property: 'inventory_item_id', type: 'integer', example: 1),
                    new OA\Property(property: 'quantity', type: 'integer', minimum: 1, example: 20),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Stock transfer created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/StockTransferResource'),
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
                description: 'Validation error or insufficient stock',
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
        path: '/stock-transfers/{stock_transfer}',
        summary: 'Get a single stock transfer',
        security: [['bearerAuth' => []]],
        tags: ['Stock Transfers'],
        parameters: [
            new OA\Parameter(name: 'stock_transfer', in: 'path', required: true, description: 'Stock transfer ID', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Stock transfer details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/StockTransferResource'),
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
}
