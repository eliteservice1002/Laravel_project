<?php

namespace App\Domains\ProductCatalog\Models;

use App\Domains\Core\Models\Concerns\HasSequentialCode;
use App\Domains\Core\Models\Concerns\HasTags;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\Marketing\Models\Slug;
use App\Domains\Tenants\Models\Store;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property int $store_id
 * @property string $name
 */
class ProductCollection extends TenantModel implements Sortable, HasMedia
{
    use HasUlid;
    use HasTranslations;
    use HasTags;
    use HasSequentialCode;
    use SortableTrait;
    use InteractsWithMedia;

    protected $translatable = ['name', 'description'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductCollectionItem::class);
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')->singleFile();
        $this->addMediaCollection('gallery');
    }

    // public function buildSortQuery(): Builder
    // {
    //     return static::query()->where('store_id', $this->store_id);
    // }

    // protected function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::createWithLocales(['ar', 'en'])
    //         ->generateSlugsFrom('name')
    //         ->saveSlugsTo('slug');
    // }
}
