<?php

namespace App\Domains\Tenants\Nova;

use App\Domains\Marketing\Nova\Slug;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Text;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;
use Spatie\NovaTranslatable\Translatable;

class Tag extends Resource
{
    use HasSortableRows;

    public static $model = \App\Domains\Tenants\Models\Tag::class;

    public static $title = 'name';

    public static $search = [
        'name',
    ];

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Translatable::make([
                Text::make('Name')
                    ->required(),

                Text::make('Slug')
                    ->required()
                    ->hideWhenCreating()
                    ->hideFromIndex(),
            ]),

            Text::make('Type'),

            new Tabs('Related', [
                MorphMany::make('Slugs', 'slugs', Slug::class),
            ]),
        ];
    }
}
