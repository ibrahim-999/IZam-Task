<?php

namespace App\Domains\Transfer\Http\Resources;

use App\Domains\Inventory\Http\Resources\InventoryItemResource;
use App\Domains\Warehouse\Http\Resources\WarehouseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockTransferResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'from_warehouse' => new WarehouseResource($this->whenLoaded('fromWarehouse')),
            'to_warehouse' => new WarehouseResource($this->whenLoaded('toWarehouse')),
            'inventory_item' => new InventoryItemResource($this->whenLoaded('inventoryItem')),
            'user_id' => $this->user_id,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
