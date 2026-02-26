<?php

namespace App\Domains\Inventory\Http\Controllers;

use App\Domains\Inventory\DTOs\InventoryItemData;
use App\Domains\Inventory\Http\Requests\StoreInventoryItemRequest;
use App\Domains\Inventory\Http\Requests\UpdateInventoryItemRequest;
use App\Domains\Inventory\Http\Resources\InventoryItemResource;
use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Inventory\Services\InventoryService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class InventoryItemController extends Controller
{
    public function __construct(private InventoryService $inventoryService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $items = $this->inventoryService->list($request->only([
            'name', 'category', 'price_min', 'price_max', 'per_page',
        ]));

        return InventoryItemResource::collection($items);
    }

    public function store(StoreInventoryItemRequest $request): JsonResponse
    {
        try {
            $item = $this->inventoryService->create(InventoryItemData::fromArray($request->validated()));

            return (new InventoryItemResource($item))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create inventory item.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(InventoryItem $inventory): InventoryItemResource
    {
        return new InventoryItemResource($inventory);
    }

    public function update(UpdateInventoryItemRequest $request, InventoryItem $inventory): InventoryItemResource|JsonResponse
    {
        try {
            $item = $this->inventoryService->update($inventory, $request->validated());

            return new InventoryItemResource($item);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update inventory item.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(InventoryItem $inventory): JsonResponse
    {
        try {
            $this->inventoryService->delete($inventory);

            return response()->json(['message' => 'Item deleted successfully.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete inventory item.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
