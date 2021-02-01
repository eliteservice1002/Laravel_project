<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\ProductCatalog\Models\ProductItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $quantity
 * @property Carbon $occurred_at
 * @property InventoryArea $area
 * @property ProductItem $productItem
 */
class InventoryMovement extends TenantModel
{
    use HasUlid;

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(InventoryArea::class, 'inventory_area_id');
    }

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function cause(): MorphTo
    {
        return $this->morphTo();
    }
}
