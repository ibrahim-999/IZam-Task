<?php

namespace App\Domains\Inventory\Services;

use App\Domains\Inventory\DTOs\InventoryItemData;
use App\Domains\Inventory\Models\InventoryItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InventoryService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        $query = InventoryItem::query();

        if (! empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (! empty($filters['price_min'])) {
            $query->where('price', '>=', $filters['price_min']);
        }

        if (! empty($filters['price_max'])) {
            $query->where('price', '<=', $filters['price_max']);
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
