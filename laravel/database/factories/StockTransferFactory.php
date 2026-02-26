<?php

namespace Database\Factories;

use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Transfer\Models\StockTransfer;
use App\Domains\Warehouse\Models\Warehouse;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockTransferFactory extends Factory
{
    protected $model = StockTransfer::class;

    public function definition(): array
    {
        return [
            'from_warehouse_id' => Warehouse::factory(),
            'to_warehouse_id' => Warehouse::factory(),
            'inventory_item_id' => InventoryItem::factory(),
            'user_id' => User::factory(),
            'quantity' => fake()->numberBetween(1, 50),
        ];
    }
}
