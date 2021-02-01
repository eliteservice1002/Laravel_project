<?php

namespace App\Domains\ProductCatalog\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\Marketing\Models\Slug;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property Collection|ProductAttributeOption[] $options
 */
class ProductAttribute extends TenantModel implements Sortable
{
    use HasUlid;
    use HasTranslations;
    use SortableTrait;

    public $sortable = ['only_sort_on' => \App\Domains\ProductCatalog\Nova\ProductType::class];

    protected $with = ['options'];

    protected array $translatable = ['name', 'description'];

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(ProductType::class, ProductTypeAttributeAssociation::getTableName())
            ->using(ProductTypeAttributeAssociation::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(ProductAttributeOption::class, 'product_attribute_id');
    }

    public function slugs(): MorphMany
    {
        return $this->morphMany(Slug::class, 'linked');
    }

    // protected function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::createWithLocales(['ar', 'en'])
    //         ->generateSlugsFrom('name')
    //         ->saveSlugsTo('slug');
    // }
}
