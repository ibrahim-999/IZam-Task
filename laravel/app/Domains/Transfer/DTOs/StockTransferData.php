<?php

namespace App\Domains\Transfer\DTOs;

readonly class StockTransferData
{
    public function __construct(
        public int $fromWarehouseId,
        public int $toWarehouseId,
        public int $inventoryItemId,
        public int $quantity,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            fromWarehouseId: $data['from_warehouse_id'],
            toWarehouseId: $data['to_warehouse_id'],
            inventoryItemId: $data['inventory_item_id'],
            quantity: $data['quantity'],
        );
    }
}
