<?php

namespace Database\Seeders;

use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Warehouse\Models\Stock;
use App\Domains\Warehouse\Models\Warehouse;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $warehouses = Warehouse::factory(3)->create();
        $items = InventoryItem::factory(10)->create();

        foreach ($warehouses as $warehouse) {
            foreach ($items->random(5) as $item) {
                Stock::factory()->create([
                    'warehouse_id' => $warehouse->id,
                    'inventory_item_id' => $item->id,
                    'quantity' => fake()->numberBetween(5, 100),
                ]);
            }
        }
    }
}
