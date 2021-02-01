<?php

namespace App\Domains\ProductCatalog\Nova;

use App\Domains\Core\Nova\Fields\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;

/**
 * @property \App\Domains\ProductCatalog\Models\ProductItemSalePrice $resource
 */
class ProductItemSalePrice extends Resource
{
    public static $model = \App\Domains\ProductCatalog\Models\ProductItemSalePrice::class;

    public static $displayInNavigation = false;

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make('Product Item', 'productItem', ProductItem::class),

            Money::make('Price')
                ->currency('SAR'),

            DateTime::make('Effective Since')
                ->default(Carbon::now()->addDay())
                ->rules(['required', 'date', 'after:now']),

            DateTime::make('Effective Until')
                ->default(Carbon::now()->endOfCentury())
                ->rules(['date', 'after:now']),
        ];
    }
}
