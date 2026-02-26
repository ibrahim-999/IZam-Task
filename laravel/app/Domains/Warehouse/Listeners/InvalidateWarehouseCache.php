<?php

namespace App\Domains\Warehouse\Listeners;

use App\Domains\Warehouse\Events\StockTransferred;
use App\Domains\Warehouse\Services\WarehouseService;

class InvalidateWarehouseCache
{
    public function __construct(private WarehouseService $warehouseService) {}

    public function handle(StockTransferred $event): void
    {
        $this->warehouseService->invalidateCache($event->fromWarehouseId);
        $this->warehouseService->invalidateCache($event->toWarehouseId);
    }
}
