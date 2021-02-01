<?php

namespace App\Domains\ProductCatalog\Events;

use App\Domains\Core\Services\ES\DomainEvent;
use App\Domains\ProductCatalog\Models\Product;

class ProductWasAddedToCatalog extends DomainEvent
{
    public Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
