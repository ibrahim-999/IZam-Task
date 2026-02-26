<?php

namespace App\Domains\Warehouse\Listeners;

use App\Domains\Warehouse\Events\LowStockDetected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendLowStockNotification implements ShouldQueue
{
    public function handle(LowStockDetected $event): void
    {
        $stock = $event->stock;

        Log::warning('Low stock detected', [
            'warehouse_id' => $stock->warehouse_id,
            'inventory_item_id' => $stock->inventory_item_id,
            'quantity' => $stock->quantity,
        ]);
    }
}
