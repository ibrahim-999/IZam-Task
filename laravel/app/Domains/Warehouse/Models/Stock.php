<?php

namespace App\Domains\Warehouse\Models;

use App\Domains\Inventory\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    public const LOW_STOCK_THRESHOLD = 10;

    protected $fillable = ['warehouse_id', 'inventory_item_id', 'quantity'];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= self::LOW_STOCK_THRESHOLD;
    }

    public function hasSufficientQuantity(int $requested): bool
    {
        return $this->quantity >= $requested;
    }

    protected static function newFactory()
    {
        return \Database\Factories\StockFactory::new();
    }
}
