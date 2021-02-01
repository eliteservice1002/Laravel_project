<?php

namespace App\Domains\Manufacturing\Models;

use App\Domains\Core\Models\Concerns\HasMoney;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\ProductCatalog\Models\ProductItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property float $quantity
 * @property WorkOrder $workOrder
 * @property ProductItem $item
 */
class WorkOrderLineItem extends TenantModel
{
    use HasUlid;
    use HasMoney;

    protected array $money = ['unit_price'];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }
}
