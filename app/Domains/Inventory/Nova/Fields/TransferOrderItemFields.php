<?php

namespace App\Domains\Inventory\Nova\Fields;

use App\Domains\ProductCatalog\Nova\ProductItem;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;

class TransferOrderItemFields
{
    public function __invoke(): array
    {
        return [
            BelongsTo::make('Item', 'item', ProductItem::class),

            Number::make('Quantity')->default(0),
        ];
    }
}
