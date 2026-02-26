<?php

namespace Database\Factories;

use App\Domains\Warehouse\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Warehouse',
            'location' => fake()->city() . ', ' . fake()->country(),
        ];
    }
}
