<?php

namespace App\Domains\ProductCatalog\Observers;

use App\Domains\ProductCatalog\Models\Enums\ProductItemIdentifierType;
use App\Domains\ProductCatalog\Models\ProductItemIdentifier;

class ProductItemIdentifierObserver
{
    public function saved(ProductItemIdentifier $identifier): void
    {
        if ($identifier->type->equals(ProductItemIdentifierType::SKU())) {
            $identifier->productItem->code = $identifier->value;
            $identifier->productItem->save();
        }
    }
}
