<?php

namespace App\Domains\ProductCatalog\Models;

use App\Domains\Core\Models\Concerns\HasEffectivePeriod;
use App\Domains\Core\Models\Concerns\HasUlidKey;
use App\Domains\Core\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property int $product_item_id
 * @property ProductItem $productItem
 */
class ProductItemPublishSchedule extends TenantModel
{
    use HasUlidKey;
    use HasEffectivePeriod;

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }
}
