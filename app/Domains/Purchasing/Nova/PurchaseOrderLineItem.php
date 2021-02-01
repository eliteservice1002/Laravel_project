<?php

namespace App\Domains\Purchasing\Nova;

use App\Domains\Core\Nova\Fields\Money;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;

/**
 * @property \App\Domains\Purchasing\Models\PurchaseOrderLineItem $resource
 */
class PurchaseOrderLineItem extends Resource
{
    public static $model = \App\Domains\Purchasing\Models\PurchaseOrderLineItem::class;

    public static $displayInNavigation = false;

    public function title(): string
    {
        return "[{$this->resource->purchaseOrder->code}] {$this->resource->product->name} ({$this->resource->quantity} {$this->resource->product->unit->symbol})";
    }

    public function fields(Request $request): array
    {
        return [
            BelongsTo::make('Purchase Order', 'purchaseOrder', PurchaseOrder::class)
                ->exceptOnForms(),

            BelongsTo::make('Product')
                ->searchable(),

            Number::make('Quantity')->default(1)
                ->required()
                ->rules('min:1'),

            Money::make('Unit Price')->currency('SAR')
                ->required(),

            Money::make('Total Price', function (\App\Domains\Purchasing\Models\PurchaseOrderLineItem $lineItem) {
                return $lineItem->unit_price->multipliedBy($lineItem->quantity);
            })
                ->currency('SAR')
                ->exceptOnForms(),
        ];
    }
}
