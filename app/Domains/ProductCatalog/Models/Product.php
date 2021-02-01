<?php

namespace App\Domains\ProductCatalog\Models;

use App\Domains\Core\Models\Concerns\HasSequentialCode;
use App\Domains\Core\Models\Concerns\HasTags;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\Marketing\Models\Slug;
use App\Domains\Tenants\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $store_id
 * @property int $product_unit_id
 * @property int $product_type_id
 * @property ProductType $type
 * @property Collection|ProductAttribute[] $attributes
 * @property Collection|ProductItem[] $productItems
 * @property ProductItem $mainProduct
 */
class Product extends TenantModel implements HasMedia
{
    use HasUlid;
    use HasTranslations;
    use HasTags;
    use HasSequentialCode;
    use InteractsWithMedia;

    protected array $translatable = ['name', 'description'];

    protected $fillable = [
        'code',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(ProductAttribute::class, ProductAttributeAssociation::getTableName())
            ->using(ProductAttributeAssociation::class);
    }

    public function productItems(): HasMany
    {
        return $this->hasMany(ProductItem::class);
    }

    // public function mainProduct(): HasOne
    // {
    //     return $this->hasOne(Product::class)->where('main', true);
    // }

    public function productCollectionItems(): MorphMany
    {
        return $this->morphMany(ProductCollectionItem::class, 'item');
    }

    public function slugs(): MorphMany
    {
        return $this->morphMany(Slug::class, 'linked');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('responsive')
            ->withResponsiveImages();

        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150);
    }
}
