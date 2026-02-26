<?php

namespace App\Domains\Warehouse\Models;

use App\Domains\Inventory\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location'];

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function inventoryItems(): BelongsToMany
    {
        return $this->belongsToMany(InventoryItem::class, 'stocks')->withPivot('quantity')->withTimestamps();
    }

    protected static function newFactory()
    {
        return \Database\Factories\WarehouseFactory::new();
    }
}
