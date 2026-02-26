<?php

namespace App\Domains\Transfer\Services;

use App\Domains\Transfer\DTOs\StockTransferData;
use App\Domains\Transfer\Exceptions\InsufficientStockException;
use App\Domains\Transfer\Models\StockTransfer;
use App\Domains\Warehouse\Events\LowStockDetected;
use App\Domains\Warehouse\Events\StockTransferred;
use App\Domains\Warehouse\Models\Stock;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StockTransferService
{
    public function list(): LengthAwarePaginator
    {
        return StockTransfer::with(['fromWarehouse', 'toWarehouse', 'inventoryItem', 'user'])->paginate(15);
    }

    public function find(int $id): StockTransfer
    {
        return StockTransfer::with(['fromWarehouse', 'toWarehouse', 'inventoryItem', 'user'])->findOrFail($id);
    }

    public function transfer(StockTransferData $data, int $userId): StockTransfer
    {
        return DB::transaction(function () use ($data, $userId) {
            $sourceStock = Stock::where('warehouse_id', $data->fromWarehouseId)
                ->where('inventory_item_id', $data->inventoryItemId)
                ->lockForUpdate()
                ->first();

            if (! $sourceStock || ! $sourceStock->hasSufficientQuantity($data->quantity)) {
                throw new InsufficientStockException();
            }

            $sourceStock->decrement('quantity', $data->quantity);

            $destinationStock = Stock::where('warehouse_id', $data->toWarehouseId)
                ->where('inventory_item_id', $data->inventoryItemId)
                ->lockForUpdate()
                ->first();

            if (! $destinationStock) {
                $destinationStock = Stock::create([
                    'warehouse_id' => $data->toWarehouseId,
                    'inventory_item_id' => $data->inventoryItemId,
                    'quantity' => 0,
                ]);
            }

            $destinationStock->increment('quantity', $data->quantity);

            $transfer = StockTransfer::create([
                'from_warehouse_id' => $data->fromWarehouseId,
                'to_warehouse_id' => $data->toWarehouseId,
                'inventory_item_id' => $data->inventoryItemId,
                'user_id' => $userId,
                'quantity' => $data->quantity,
            ]);

            $sourceStock->refresh();
            if ($sourceStock->isLowStock()) {
                LowStockDetected::dispatch($sourceStock);
            }

            StockTransferred::dispatch($data->fromWarehouseId, $data->toWarehouseId);

            return $transfer->load(['fromWarehouse', 'toWarehouse', 'inventoryItem', 'user']);
        });
    }
}
