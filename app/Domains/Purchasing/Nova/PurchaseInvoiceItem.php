<?php

namespace App\Domains\Purchasing\Nova;

use App\Domains\ProductCatalog\Nova\ProductItem;
use App\Domains\ProductCatalog\Nova\ProductUnit;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;

/**
 * @property \App\Domains\Purchasing\Models\PurchaseInvoiceLineItem $resource
 */
class PurchaseInvoiceItem extends Resource
{
    public static $model = \App\Domains\Purchasing\Models\PurchaseInvoiceLineItem::class;

    public static $displayInNavigation = false;

    public function title(): string
    {
        return "{$this->resource->item->name} x {$this->resource->quantity} {$this->resource->item->unit->name}";
    }

    public function fields(Request $request): array
    {
        return [
            BelongsTo::make('Purchase Invoice', 'purchaseInvoice', PurchaseInvoice::class)
                ->exceptOnForms(),

            BelongsTo::make('Item', 'item', ProductItem::class)
                ->searchable(),

            Number::make('Quantity')->default(0),

            BelongsTo::make('Unit', 'unit', ProductUnit::class),
        ];
    }
}
