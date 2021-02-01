<?php

namespace App\Domains\ProductCatalog\Models;

use App\Domains\Core\Models\Concerns\HasEffectivePeriod;
use App\Domains\Core\Models\Concerns\HasMoney;
use App\Domains\Core\Models\Concerns\HasUlidKey;
use App\Domains\Core\Models\TenantModel;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property int $product_item_id
 * @property Money $price
 * @property ProductItem $productItem
 */
class ProductItemSalePrice extends TenantModel
{
    use HasUlidKey;
    use HasEffectivePeriod;
    use HasMoney;

    protected array $money = ['price'];

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }
}
