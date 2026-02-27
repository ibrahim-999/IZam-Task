<?php

namespace App\Domains\Warehouse\Services;

use App\Domains\Warehouse\Models\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    public function getInventory(Warehouse $warehouse, int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        $version = Cache::get("warehouse:{$warehouse->id}:inventory:version", 1);

        return Cache::remember(
            "warehouse:{$warehouse->id}:inventory:v{$version}:page:{$page}:perPage:{$perPage}",
            now()->addMinutes(10),
            fn () => $warehouse->stocks()->with('inventoryItem')->paginate($perPage, ['*'], 'page', $page)
        );
    }

    public function invalidateCache(int $warehouseId): void
    {
        Cache::increment("warehouse:{$warehouseId}:inventory:version");
    }
}
