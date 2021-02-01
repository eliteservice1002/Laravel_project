<?php

namespace App\Domains\Purchasing\Models;

use App\Domains\Core\Models\Concerns\HasMoney;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\ProductCatalog\Models\ProductItem;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $quantity
 * @property Money $unit_price
 * @property PurchaseOrder $purchaseOrder
 * @property ProductItem $productItem
 */
class PurchaseOrderLineItem extends TenantModel
{
    use HasUlid;
    use HasMoney;

    protected array $money = ['unit_price'];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }
}
