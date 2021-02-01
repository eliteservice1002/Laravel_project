<?php

namespace App\Domains\Purchasing\Models;

use App\Domains\Core\Models\Concerns\HasMoney;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\ProductCatalog\Models\ProductItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property float $quantity
 * @property PurchaseInvoice $purchaseInvoice
 * @property ProductItem $item
 */
class PurchaseInvoiceLineItem extends TenantModel
{
    use HasUlid;
    use HasMoney;

    protected array $money = ['unit_price'];

    public function purchaseInvoice(): BelongsTo
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }
}
