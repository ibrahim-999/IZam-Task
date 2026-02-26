<?php

namespace Database\Factories;

use App\Domains\Inventory\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####-??')),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 1, 999),
            'category' => fake()->randomElement(['Electronics', 'Furniture', 'Clothing', 'Food', 'Tools']),
        ];
    }
}
