<?php

namespace App\Domains\Inventory\Nova\Fields;

use Laravel\Nova\Fields\Number;

class InventoryItemFields
{
    public function __invoke(): array
    {
        return [
            Number::make('Stock')->default(0),
        ];
    }
}
