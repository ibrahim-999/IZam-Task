<?php

namespace App\Domains\Warehouse\Http\Resources;

use App\Domains\Inventory\Http\Resources\InventoryItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'warehouse_id' => $this->warehouse_id,
            'inventory_item_id' => $this->inventory_item_id,
            'quantity' => $this->quantity,
            'inventory_item' => new InventoryItemResource($this->whenLoaded('inventoryItem')),
            'warehouse' => new WarehouseResource($this->whenLoaded('warehouse')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
