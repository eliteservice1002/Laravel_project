<?php

namespace App\Domains\Marketing\Nova;

use App\Domains\ProductCatalog\Nova\Product;
use App\Domains\ProductCatalog\Nova\ProductCollection;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;

class ContentPageBlockItem extends Resource
{
    public static $model = \App\Domains\Marketing\Models\ContentPageBlockItem::class;

    public static $displayInNavigation = false;

    public static function label()
    {
        return 'Block Items';
    }

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'))->sortable(),

            BelongsTo::make('Block', 'block', ContentPageBlock::class),

            MorphTo::make('Link', 'link')
                ->types([
                    Product::class,
                    ProductCollection::class,
                ]),
        ];
    }
}
