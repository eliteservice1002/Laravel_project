<?php

namespace App\Domains\ProductCatalog\Nova;

use App\Domains\ProductCatalog\Models\Enums\ProductItemIdentifierType;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

/**
 * @property \App\Domains\ProductCatalog\Models\ProductItemIdentifier $resource
 */
class ProductItemIdentifier extends Resource
{
    public static $model = \App\Domains\ProductCatalog\Models\ProductItemIdentifier::class;

    public static $displayInNavigation = false;

    public function title(): string
    {
        return "[{$this->resource->type}] {$this->resource->value}";
    }

    public function fields(Request $request): array
    {
        return [
            BelongsTo::make('Product Item', 'productItem', ProductItem::class),

            Select::make('Type')
                ->options(ProductItemIdentifierType::toArray())
                ->required(),

            Text::make('Value')
                ->sortable()
                ->required(),
        ];
    }

    public static function newModel()
    {
        $model = parent::newModel();

        $model->type = ProductItemIdentifierType::SKU();

        return $model;
    }
}
