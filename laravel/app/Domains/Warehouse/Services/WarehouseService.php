<?php

namespace App\Domains\Warehouse\Services;

use App\Domains\Warehouse\Models\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class WarehouseService
{
    public function list(): LengthAwarePaginator
    {
        return Warehouse::paginate(15);
    }

    public function create(array $data): Warehouse
    {
        return Warehouse::create($data);
    }

    public function find(int $id): Warehouse
    {
        return Warehouse::findOrFail($id);
    }

    public function update(Warehouse $warehouse, array $data): Warehouse
    {
        $warehouse->update($data);

        return $warehouse;
    }

    public function delete(Warehouse $warehouse): void
    {
        $warehouse->delete();
    }

    public function getInventory(Warehouse $warehouse): Collection
    {
        return Cache::remember(
            "warehouse:{$warehouse->id}:inventory",
            now()->addMinutes(10),
            fn () => $warehouse->stocks()->with('inventoryItem')->get()
        );
    }

    public function invalidateCache(int $warehouseId): void
    {
        Cache::forget("warehouse:{$warehouseId}:inventory");
    }
}
