<?php

namespace Tests\Unit;

use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Transfer\DTOs\StockTransferData;
use App\Domains\Transfer\Exceptions\InsufficientStockException;
use App\Domains\Transfer\Services\StockTransferService;
use App\Domains\Warehouse\Models\Stock;
use App\Domains\Warehouse\Models\Warehouse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTransferServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejects_transfer_when_insufficient_stock(): void
    {
        $user = User::factory()->create();
        $fromWarehouse = Warehouse::factory()->create();
        $toWarehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        Stock::factory()->create([
            'warehouse_id' => $fromWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 5,
        ]);

        $service = new StockTransferService();

        $this->expectException(InsufficientStockException::class);

        $service->transfer(StockTransferData::fromArray([
            'from_warehouse_id' => $fromWarehouse->id,
            'to_warehouse_id' => $toWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 10,
        ]), $user->id);
    }

    public function test_rejects_transfer_when_no_stock_exists(): void
    {
        $user = User::factory()->create();
        $fromWarehouse = Warehouse::factory()->create();
        $toWarehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        $service = new StockTransferService();

        $this->expectException(InsufficientStockException::class);

        $service->transfer(StockTransferData::fromArray([
            'from_warehouse_id' => $fromWarehouse->id,
            'to_warehouse_id' => $toWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 1,
        ]), $user->id);
    }
}
