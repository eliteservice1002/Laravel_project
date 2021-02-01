<?php

namespace App\Domains\Manufacturing\Nova;

use App\Domains\Core\Nova\Fields\Money;
use App\Domains\ProductCatalog\Nova\ProductItem;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;

/**
 * @property \App\Domains\Manufacturing\Models\WorkOrderLineItem $resource
 */
class WorkOrderItem extends Resource
{
    public static $model = \App\Domains\Manufacturing\Models\WorkOrderLineItem::class;

    public static $displayInNavigation = false;

    public function title(): string
    {
        return "{$this->resource->item->name} x {$this->resource->quantity} {$this->resource->productUnit->name}";
    }

    public function fields(Request $request): array
    {
        return [
            BelongsTo::make('Purchase Order', 'purchaseOrder', WorkOrder::class)
                ->exceptOnForms(),

            BelongsTo::make('Item', 'item', ProductItem::class)
                ->searchable(),

            Number::make('Quantity')->default(1)
                ->required()
                ->rules('min:1'),

            Money::make('Unit Price')->currency('SAR')
                ->required(),

            Money::make('Unit Price')->currency('SAR')
                ->resolveUsing(fn ($value, $model, $attribute) => $model->productUnit_price)
                ->exceptOnForms(),
        ];
    }
}
