<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Core\Models\Concerns\HasUlidKey;
use App\Domains\Core\Models\TenantPivot;
use App\Domains\ProductCatalog\Models\ProductItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property int $stock
 * @property InventoryArea $inventoryArea
 * @property ProductItem $productItem
 */
class InventoryItem extends TenantPivot
{
    use HasUlidKey;

    protected $casts = [
        'stock' => 'int',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(InventoryArea::class, 'inventory_area_id');
    }

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }
}
