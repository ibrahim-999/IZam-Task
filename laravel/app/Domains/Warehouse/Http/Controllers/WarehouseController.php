<?php

namespace App\Domains\Warehouse\Http\Controllers;

use App\Domains\Warehouse\Http\Requests\StoreWarehouseRequest;
use App\Domains\Warehouse\Http\Requests\UpdateWarehouseRequest;
use App\Domains\Warehouse\Http\Resources\StockResource;
use App\Domains\Warehouse\Http\Resources\WarehouseResource;
use App\Domains\Warehouse\Models\Warehouse;
use App\Domains\Warehouse\Services\WarehouseService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class WarehouseController extends Controller
{
    public function __construct(private WarehouseService $warehouseService) {}

    public function index(): AnonymousResourceCollection
    {
        return WarehouseResource::collection($this->warehouseService->list());
    }

    public function store(StoreWarehouseRequest $request): JsonResponse
    {
        try {
            $warehouse = $this->warehouseService->create($request->validated());

            return (new WarehouseResource($warehouse))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create warehouse.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Warehouse $warehouse): WarehouseResource
    {
        return new WarehouseResource($warehouse);
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): WarehouseResource|JsonResponse
    {
        try {
            $warehouse = $this->warehouseService->update($warehouse, $request->validated());

            return new WarehouseResource($warehouse);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update warehouse.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Warehouse $warehouse): JsonResponse
    {
        try {
            $this->warehouseService->delete($warehouse);

            return response()->json(['message' => 'Warehouse deleted successfully.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete warehouse.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function inventory(Request $request, Warehouse $warehouse): AnonymousResourceCollection
    {
        $stocks = $this->warehouseService->getInventory(
            $warehouse,
            (int) $request->input('per_page', 15),
            (int) $request->input('page', 1)
        );

        return StockResource::collection($stocks);
    }
}
