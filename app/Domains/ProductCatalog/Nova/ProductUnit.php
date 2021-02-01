<?php

namespace App\Domains\ProductCatalog\Nova;

use App\Domains\Marketing\Nova\Slug;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Text;
use Spatie\NovaTranslatable\Translatable;

/**
 * @property \App\Domains\ProductCatalog\Models\ProductUnit $resource
 */
class ProductUnit extends Resource
{
    public static $model = \App\Domains\ProductCatalog\Models\ProductUnit::class;

    public static $displayInNavigation = false;

    public static $title = 'name';

    public static $search = [
        'name',
        'symbol',
    ];

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Translatable::make([
                Text::make('Name'),

                Text::make('Symbol'),
            ]),

            new Tabs('Related', [
                MorphMany::make('Slugs', 'slugs', Slug::class),
            ]),
        ];
    }
}
