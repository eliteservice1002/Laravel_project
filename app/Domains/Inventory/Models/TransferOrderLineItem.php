<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\ProductCatalog\Models\ProductItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property int $quantity
 * @property int $transfer_order_id
 * @property int $product_id
 * @property TransferOrder $transferOrder
 * @property ProductItem $productItem
 */
class TransferOrderLineItem extends TenantModel
{
    use HasUlid;

    protected $fillable = [
        'product_item_id',
    ];

    public function transferOrder(): BelongsTo
    {
        return $this->belongsTo(TransferOrder::class);
    }

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }
}
