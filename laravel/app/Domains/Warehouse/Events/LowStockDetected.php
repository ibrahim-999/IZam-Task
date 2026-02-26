<?php

namespace App\Domains\Warehouse\Events;

use App\Domains\Warehouse\Models\Stock;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LowStockDetected
{
    use Dispatchable, SerializesModels;

    public function __construct(public Stock $stock) {}
}
