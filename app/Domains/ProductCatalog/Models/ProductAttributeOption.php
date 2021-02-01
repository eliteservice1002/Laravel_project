<?php

namespace App\Domains\ProductCatalog\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\Marketing\Models\Slug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property int $product_attribute_id
 * @property ProductAttribute $attribute
 */
class ProductAttributeOption extends TenantModel implements Sortable
{
    use HasUlid;
    use HasTranslations;
    use SortableTrait;

    protected array $translatable = ['name', 'description'];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }

    public function slugs(): MorphMany
    {
        return $this->morphMany(Slug::class, 'linked');
    }

    public function buildSortQuery(): Builder
    {
        return static::query()->where('product_attribute_id', $this->product_attribute_id);
    }

    // protected function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::createWithLocales(['ar', 'en'])
    //         ->generateSlugsFrom('name')
    //         ->saveSlugsTo('slug');
    // }
}
