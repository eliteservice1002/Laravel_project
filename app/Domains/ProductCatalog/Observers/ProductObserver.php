<?php

namespace App\Domains\ProductCatalog\Observers;

use App\Domains\ProductCatalog\Actions\GenerateProductSlugs;
use App\Domains\ProductCatalog\Models\Product;

class ProductObserver
{
    public function creating(Product $product): void
    {
    }

    public function created(Product $product): void
    {
        $product->attributes()->sync($product->type->attributes->modelKeys());

        // (new SyncProductItemsAction())->execute($product);

        GenerateProductSlugs::run($product);
    }

    public function updated(Product $product): void
    {
        // (new SyncProductItemsAction())->execute($product);

        GenerateProductSlugs::run($product);
    }
}
