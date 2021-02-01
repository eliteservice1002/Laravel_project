<?php

namespace App\Domains\ProductCatalog\Models;

use App\Domains\Core\Models\TenantPivot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property int $product_type_id
 * @property int $product_attribute_id
 */
class ProductTypeAttributeAssociation extends TenantPivot implements Sortable
{
    use SortableTrait;

    public array $sortable = ['only_sort_on' => \App\Domains\ProductCatalog\Nova\ProductType::class];

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function buildSortQuery(): Builder
    {
        return static::query()
            ->where('product_type_id', $this->product_type_id);
    }
}
