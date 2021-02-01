<?php

namespace App\Domains\ProductCatalog\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphTo;
use OptimistDigital\NovaSortable\Traits\HasSortableManyToManyRows;

/**
 * @property \App\Domains\ProductCatalog\Models\ProductCollectionItem $resource
 */
class ProductCollectionItem extends Resource
{
    use HasSortableManyToManyRows;

    public static $model = \App\Domains\ProductCatalog\Models\ProductCollectionItem::class;

    public static $displayInNavigation = false;

    public function title()
    {
        return "{$this->resource->item->name}";
    }

    public function fields(Request $request): array
    {
        return [
            BelongsTo::make('Product Collection', 'productCollection', ProductCollection::class)
                ->searchable(),

            MorphTo::make('Item')
                ->types([
                    ProductCollection::class,
                    Product::class,
                    ProductItem::class,
                ])
                ->searchable(),
        ];
    }
}
