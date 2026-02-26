<?php

namespace App\Providers;

use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Warehouse\Events\LowStockDetected;
use App\Domains\Warehouse\Events\StockTransferred;
use App\Domains\Warehouse\Listeners\InvalidateWarehouseCache;
use App\Domains\Warehouse\Listeners\SendLowStockNotification;
use App\Domains\Warehouse\Models\Warehouse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Route::model('inventory', InventoryItem::class);
        Route::model('warehouse', Warehouse::class);

        Event::listen(LowStockDetected::class, SendLowStockNotification::class);
        Event::listen(StockTransferred::class, InvalidateWarehouseCache::class);
    }
}
