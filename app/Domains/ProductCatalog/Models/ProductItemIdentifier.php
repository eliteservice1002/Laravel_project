<?php

namespace App\Domains\ProductCatalog\Models;

use App\Domains\Core\Models\Concerns\HasUlidKey;
use App\Domains\Core\Models\TenantModel;
use App\Domains\ProductCatalog\Models\Enums\ProductItemIdentifierType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property int $product_item_id
 * @property string $value
 * @property ProductItemIdentifierType $type
 * @property ProductItem $productItem
 */
class ProductItemIdentifier extends TenantModel
{
    use HasUlidKey;

    protected $casts = [
        'type' => ProductItemIdentifierType::class,
    ];

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }
}
