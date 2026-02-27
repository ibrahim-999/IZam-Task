<?php

namespace App\Domains\Inventory\Services;

use App\Domains\Inventory\DTOs\InventoryItemData;
use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Warehouse\Models\Stock;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InventoryService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        $query = Stock::with(['inventoryItem', 'warehouse']);

        if (! empty($filters['warehouse_id'])) {
            $query->where('warehouse_id', $filters['warehouse_id']);
        }

        if (! empty($filters['name'])) {
            $query->whereHas('inventoryItem', fn ($q) => $q->where('name', 'like', '%' . $filters['name'] . '%'));
        }

        if (! empty($filters['category'])) {
            $query->whereHas('inventoryItem', fn ($q) => $q->where('category', $filters['category']));
        }

        if (! empty($filters['price_min'])) {
            $query->whereHas('inventoryItem', fn ($q) => $q->where('price', '>=', $filters['price_min']));
        }

        if (! empty($filters['price_max'])) {
            $query->whereHas('inventoryItem', fn ($q) => $q->where('price', '<=', $filters['price_max']));
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function create(InventoryItemData $data): InventoryItem
    {
        return InventoryItem::create($data->toArray());
    }

    public function find(int $id): InventoryItem
    {
        return InventoryItem::findOrFail($id);
    }

    public function update(InventoryItem $item, array $data): InventoryItem
    {
        $item->update($data);

        return $item;
    }

    public function delete(InventoryItem $item): void
    {
        $item->delete();
    }
}
