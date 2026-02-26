<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Warehouse\Models\Stock;
use App\Domains\Warehouse\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sku', 'description', 'price', 'category'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'stocks')->withPivot('quantity')->withTimestamps();
    }

    protected static function newFactory()
    {
        return \Database\Factories\InventoryItemFactory::new();
    }
}
