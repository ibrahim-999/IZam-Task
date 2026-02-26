<?php

namespace Tests\Feature;

use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Warehouse\Events\LowStockDetected;
use App\Domains\Warehouse\Models\Stock;
use App\Domains\Warehouse\Models\Warehouse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LowStockEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_low_stock_event_is_dispatched_when_stock_falls_to_threshold(): void
    {
        Event::fake([LowStockDetected::class]);

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $fromWarehouse = Warehouse::factory()->create();
        $toWarehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        Stock::factory()->create([
            'warehouse_id' => $fromWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 15,
        ]);

        $this->postJson('/api/v1/stock-transfers', [
            'from_warehouse_id' => $fromWarehouse->id,
            'to_warehouse_id' => $toWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 10,
        ]);

        Event::assertDispatched(LowStockDetected::class, function ($event) use ($fromWarehouse, $item) {
            return $event->stock->warehouse_id === $fromWarehouse->id
                && $event->stock->inventory_item_id === $item->id;
        });
    }

    public function test_low_stock_event_is_not_dispatched_when_stock_above_threshold(): void
    {
        Event::fake([LowStockDetected::class]);

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $fromWarehouse = Warehouse::factory()->create();
        $toWarehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        Stock::factory()->create([
            'warehouse_id' => $fromWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 100,
        ]);

        $this->postJson('/api/v1/stock-transfers', [
            'from_warehouse_id' => $fromWarehouse->id,
            'to_warehouse_id' => $toWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 5,
        ]);

        Event::assertNotDispatched(LowStockDetected::class);
    }
}
