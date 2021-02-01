<?php

namespace App\Domains\ProductCatalog\Models;

use App\Domains\Core\Models\Concerns\HasTags;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Marketing\Models\Slug;
use App\Domains\ProductCatalog\Models\Concerns\ProductScopes;
use App\Domains\ProductCatalog\Models\Concerns\Publishable;
use App\Domains\ProductCatalog\Models\Enums\ProductItemIdentifierType;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $product_id
 * @property int $product_unit_id
 * @property int $product_type_id
 * @property Product $product
 * @property ProductUnit $unit
 * @property Collection|ProductItemSalePrice[] $salePrices
 * @property Collection|ProductItemIdentifier[] $identifiers
 * @property Collection|ProductAttributeOption[] $attributeOptions
 * @property InventoryItem $pivot
 */
class ProductItem extends TenantModel implements Sortable, HasMedia
{
    use HasUlid;
    use HasTranslations;
    use HasTags;
    use SortableTrait;
    use ProductScopes;
    use Publishable;
    use InteractsWithMedia;

    protected $with = ['identifiers', 'salePrices'];

    protected $casts = [
        'main' => 'bool',
    ];

    protected array $translatable = ['name'];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function salePrices(): HasMany
    {
        return $this->hasMany(ProductItemSalePrice::class);
    }

    public function identifiers(): HasMany
    {
        return $this->hasMany(ProductItemIdentifier::class);
    }

    public function attributeOptions(): BelongsToMany
    {
        return $this->belongsToMany(ProductAttributeOption::class,
            ProductItemAttributeOptionAssociation::getTableName())
            ->using(ProductItemAttributeOptionAssociation::class);
    }

    public function productCollectionItems(): MorphMany
    {
        return $this->morphMany(ProductCollectionItem::class, 'item');
    }

    public function slugs(): MorphMany
    {
        return $this->morphMany(Slug::class, 'linked');
    }

    public function getPriceAttribute(): Money
    {
        /** @var ProductItemSalePrice $price */
        $price = $this->salePrices()->firstOrNew();

        return $price->price;
    }

    public function getSkuAttribute(): string
    {
        try {
            /** @var ProductItemIdentifier $sku */
            $sku = $this->identifiers()->where('type', ProductItemIdentifierType::SKU())->firstOrFail();
        } catch (ModelNotFoundException) {
            return '';
        }

        return $sku->value;
    }

    // protected function setPriceAttribute(Money $value): self
    // {
    //     // TODO(ibrasho): Abstract this logic to HasEffectivePeriod trait.
    //     if ( ! $this->exists) {
    //         return $this;
    //     }
    //
    //     /** @var ProductSalePrice $oldPrice */
    //     $oldPrice = $this->salePrices()->firstOrNew();
    //     if ($oldPrice->exists) {
    //         $oldPriceEffectiveUntil = $oldPrice->effective_until;
    //         $oldPrice->effective_until = Carbon::now();
    //         $oldPrice->save();
    //     }
    //
    //     /** @var ProductSalePrice $newPrice */
    //     $newPrice = $this->salePrices()->make();
    //     $newPrice->effective_since = optional($oldPrice->effective_until)->addSecond() ?? Carbon::now();
    //     $newPrice->price = $value;
    //     $newPrice->save();
    //
    //     return $this;
    // }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('responsive')
            ->withResponsiveImages();

        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150);
    }

    public function toSearchableArray(): array
    {
        $array = $this->toArray();

        $array['images'] = $this->getMedia()
            ->map(fn (Media $media) => $media->getUrl('responsive'));

        return $array;
    }

    // protected function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::createWithLocales(['ar', 'en'])
    //         ->generateSlugsFrom(function (ProductItem $item, $locale) {
    //             $productSlug = $item->product->getTranslation('slug', $locale);
    //
    //             if (empty($this->name)) {
    //                 return "{$productSlug}";
    //             }
    //
    //             return "{$productSlug}-{$this->name}";
    //         })
    //         ->saveSlugsTo('slug');
    // }

    protected function makeAllSearchableUsing($query)
    {
        return $query->with('product');
    }
}
