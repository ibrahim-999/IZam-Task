<?php

namespace App\Domains\Warehouse\Listeners;

use App\Domains\Warehouse\Events\LowStockDetected;
use App\Domains\Warehouse\Notifications\LowStockNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendLowStockNotification implements ShouldQueue
{
    public function handle(LowStockDetected $event): void
    {
        $stock = $event->stock;

        Notification::route('mail', config('app.admin_email', 'admin@izam.co'))
            ->notify(new LowStockNotification($stock));

        Log::warning('Low stock detected', [
            'warehouse_id' => $stock->warehouse_id,
            'inventory_item_id' => $stock->inventory_item_id,
            'quantity' => $stock->quantity,
        ]);
    }
}
