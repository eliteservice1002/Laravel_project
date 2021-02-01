<?php

namespace App\Domains\Marketing\Nova;

use App\Domains\ProductCatalog\Nova\Product;
use App\Domains\ProductCatalog\Nova\ProductAttribute;
use App\Domains\ProductCatalog\Nova\ProductAttributeOption;
use App\Domains\ProductCatalog\Nova\ProductCollection;
use App\Domains\ProductCatalog\Nova\ProductItem;
use App\Domains\ProductCatalog\Nova\ProductType;
use App\Domains\Tenants\Nova\Tag;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Slug extends Resource
{
    public static $model = \App\Domains\Marketing\Models\Slug::class;

    public static $displayInNavigation = true;

    public static $search = [
        'path',
    ];

    public function fields(Request $request): array
    {
        return [
            Text::make('Path'),

            Select::make('Locale')
                ->options(['ar' => 'AR', 'en' => 'EN']),

            MorphTo::make('Linked')
                ->types([
                    Tag::class,
                    Product::class,
                    ProductCollection::class,
                    ProductItem::class,
                    ProductAttribute::class,
                    ProductAttributeOption::class,
                    ProductType::class,
                ]),
        ];
    }
}
