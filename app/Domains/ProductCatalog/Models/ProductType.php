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
 * @property string $ulid
 * @property string $name
 * @property string $code
 * @property Collection|ProductAttribute[] $attributes
 * @property Collection|Slug[] $slugs
 */
class ProductType extends TenantModel implements Sortable
{
    use HasUlid;
    use HasTranslations;
    use SortableTrait;

    protected array $translatable = ['name'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(ProductAttribute::class, ProductTypeAttributeAssociation::getTableName())
            ->using(ProductTypeAttributeAssociation::class);
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
