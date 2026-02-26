<?php

namespace App\Domains\Inventory\DTOs;

readonly class InventoryItemData
{
    public function __construct(
        public string $name,
        public string $sku,
        public ?string $description,
        public float $price,
        public string $category,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            sku: $data['sku'],
            description: $data['description'] ?? null,
            price: $data['price'],
            category: $data['category'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->category,
        ];
    }
}
