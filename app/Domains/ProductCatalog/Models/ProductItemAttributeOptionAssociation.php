<?php

namespace App\Domains\ProductCatalog\Models;

use App\Domains\Core\Models\TenantPivot;

/**
 * @property int $product_item_id
 * @property int $product_attribute_option_id
 */
class ProductItemAttributeOptionAssociation extends TenantPivot
{
    public $sortable = ['only_sort_on' => \App\Domains\ProductCatalog\Nova\ProductItem::class];
}
