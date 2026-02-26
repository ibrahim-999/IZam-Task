<?php

namespace Database\Factories;

use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Warehouse\Models\Stock;
use App\Domains\Warehouse\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition(): array
    {
        return [
            'warehouse_id' => Warehouse::factory(),
            'inventory_item_id' => InventoryItem::factory(),
            'quantity' => fake()->numberBetween(0, 500),
        ];
    }
}
