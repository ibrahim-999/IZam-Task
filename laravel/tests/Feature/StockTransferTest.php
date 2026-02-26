<?php

namespace Tests\Feature;

use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Warehouse\Models\Stock;
use App\Domains\Warehouse\Models\Warehouse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StockTransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_stock_transfer_via_api(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $fromWarehouse = Warehouse::factory()->create();
        $toWarehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        Stock::factory()->create([
            'warehouse_id' => $fromWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 50,
        ]);

        $response = $this->postJson('/api/v1/stock-transfers', [
            'from_warehouse_id' => $fromWarehouse->id,
            'to_warehouse_id' => $toWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 20,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.quantity', 20);

        $this->assertDatabaseHas('stocks', [
            'warehouse_id' => $fromWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 30,
        ]);

        $this->assertDatabaseHas('stocks', [
            'warehouse_id' => $toWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 20,
        ]);

        $this->assertDatabaseHas('stock_transfers', [
            'from_warehouse_id' => $fromWarehouse->id,
            'to_warehouse_id' => $toWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 20,
            'user_id' => $user->id,
        ]);
    }

    public function test_transfer_fails_with_insufficient_stock(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $fromWarehouse = Warehouse::factory()->create();
        $toWarehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        Stock::factory()->create([
            'warehouse_id' => $fromWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 5,
        ]);

        $response = $this->postJson('/api/v1/stock-transfers', [
            'from_warehouse_id' => $fromWarehouse->id,
            'to_warehouse_id' => $toWarehouse->id,
            'inventory_item_id' => $item->id,
            'quantity' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('quantity');
    }

    public function test_transfer_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/stock-transfers', [
            'from_warehouse_id' => 1,
            'to_warehouse_id' => 2,
            'inventory_item_id' => 1,
            'quantity' => 5,
        ]);

        $response->assertStatus(401);
    }
}
