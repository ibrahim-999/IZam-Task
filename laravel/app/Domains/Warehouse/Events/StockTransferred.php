<?php

namespace App\Domains\Warehouse\Events;

use Illuminate\Foundation\Events\Dispatchable;

class StockTransferred
{
    use Dispatchable;

    public function __construct(
        public int $fromWarehouseId,
        public int $toWarehouseId,
    ) {}
}
