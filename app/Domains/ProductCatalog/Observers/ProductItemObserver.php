<?php

namespace App\Domains\ProductCatalog\Observers;

use App\Domains\ProductCatalog\Actions\GenerateProductItemSlugs;
use App\Domains\ProductCatalog\Models\Enums\ProductItemIdentifierType;
use App\Domains\ProductCatalog\Models\ProductItem;

class ProductItemObserver
{
    public function creating(ProductItem $productItem): void
    {
    }

    public function created(ProductItem $productItem): void
    {
        GenerateProductItemSlugs::run($productItem);

        $productItem->identifiers()->firstOrCreate([
            'type' => ProductItemIdentifierType::SKU(),
            'value' => $productItem->code,
        ]);
    }

    public function updated(ProductItem $productItem): void
    {
        GenerateProductItemSlugs::run($productItem);
    }
}
