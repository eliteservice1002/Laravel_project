<?php

namespace App\Domains\Inventory\Nova;

use App\Domains\Purchasing\Nova\PurchaseOrderLineItem;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Number;
use OptimistDigtal\NovaMultiselectFilter\MultiselectFilter;

class InventoryMovement extends Resource
{
    public static $model = \App\Domains\Inventory\Models\InventoryMovement::class;

    public static $displayInNavigation = false;

    public static $title = 'id';

    public function fields(Request $request): array
    {
        return [
            BelongsTo::make('Area', 'area', InventoryArea::class)
                ->readonly(),

            BelongsTo::make('Product')
                ->readonly(),

            Number::make('Quantity')->default(0)
                ->readonly(),

            MorphTo::make('Cause')
                ->types([
                    PurchaseOrderLineItem::class,
                ])
                ->readonly(),

            DateTime::make('Occurred At')
                ->readonly(),
        ];
    }

    public function filters(Request $request): array
    {
        return array_merge([
            new class() extends MultiselectFilter {
                public $name = 'Location Area';

                public function apply(Request $request, $query, $value)
                {
                    return $query->whereIn('inventory_area_id', $value);
                }

                public function options(Request $request): \Illuminate\Support\Collection | array
                {
                    return \App\Domains\Inventory\Models\InventoryArea::query()
                        ->pluck('name', 'id');
                }
            },
        ], parent::filters($request));
    }
}
