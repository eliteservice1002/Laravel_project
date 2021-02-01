<?php

namespace App\Domains\ProductCatalog\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property int $id
 * @property int $product_collection_id
 * @property string $item_type
 * @property int $item_id
 * @property ProductCollection $product_collection
 * @property Product|ProductCollection|ProductItem $item
 */
class ProductCollectionItem extends TenantModel implements Sortable
{
    use HasUlid;
    use SortableTrait;

    public array $sortable = ['only_sort_on' => \App\Domains\ProductCatalog\Nova\ProductCollection::class];

    public function productCollection(): BelongsTo
    {
        return $this->belongsTo(ProductCollection::class);
    }

    public function item(): BelongsTo
    {
        return $this->morphTo();
    }

    public function buildSortQuery(): Builder
    {
        return static::query()
            ->where('product_collection_id', $this->product_collection_id);
    }
}
