<?php

namespace App\Domains\Inventory\Nova;

use App\Domains\ProductCatalog\Nova\ProductItem;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;

/**
 * @property \App\Domains\Inventory\Models\InventoryItem $resource
 */
class InventoryItem extends Resource
{
    public static $model = \App\Domains\Inventory\Models\InventoryItem::class;

    public static $displayInNavigation = false;

    public static $searchRelations = [
        'product' => [
            'name',
        ],
    ];

    public function title(): string
    {
        return "{$this->resource->productItem->name}";
    }

    public function fields(Request $request): array
    {
        return [
            BelongsTo::make('Area', 'area', InventoryArea::class),

            BelongsTo::make('Product Item', 'productItem', ProductItem::class),

            Number::make('Stock'),
        ];
    }
}
