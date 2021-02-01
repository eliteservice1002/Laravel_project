<?php

namespace App\Domains\Marketing\Models;

use App\Domains\Core\Models\Concerns\HasUlidKey;
use App\Domains\Core\Models\TenantModel;
use App\Domains\ProductCatalog\Models\Product;
use App\Domains\ProductCatalog\Models\ProductAttribute;
use App\Domains\ProductCatalog\Models\ProductAttributeOption;
use App\Domains\ProductCatalog\Models\ProductCollection;
use App\Domains\ProductCatalog\Models\ProductItem;
use App\Domains\ProductCatalog\Models\ProductType;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id
 * @property string $path
 * @property string $locale
 * @property int $linked_id
 * @property string $linked_type
 * @property Product|ProductAttribute|ProductAttributeOption|ProductCollection|ProductItem|ProductType $linked
 */
class Slug extends TenantModel
{
    use HasUlidKey;

    protected $fillable = [
        'path',
        'locale',
    ];

    public function linked(): MorphTo
    {
        return $this->morphTo();
    }
}
