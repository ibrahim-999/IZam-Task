<?php

namespace App\Domains\Transfer\Http\Controllers;

use App\Domains\Transfer\DTOs\StockTransferData;
use App\Domains\Transfer\Exceptions\InsufficientStockException;
use App\Domains\Transfer\Http\Requests\StoreStockTransferRequest;
use App\Domains\Transfer\Http\Resources\StockTransferResource;
use App\Domains\Transfer\Services\StockTransferService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class StockTransferController extends Controller
{
    public function __construct(private StockTransferService $stockTransferService) {}

    public function index(): AnonymousResourceCollection
    {
        return StockTransferResource::collection($this->stockTransferService->list());
    }

    public function store(StoreStockTransferRequest $request): JsonResponse
    {
        try {
            $transfer = $this->stockTransferService->transfer(
                StockTransferData::fromArray($request->validated()),
                $request->user()->id
            );

            return (new StockTransferResource($transfer))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (InsufficientStockException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create stock transfer.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id): StockTransferResource
    {
        return new StockTransferResource($this->stockTransferService->find($id));
    }
}
