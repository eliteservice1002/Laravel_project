<?php

namespace App\Domains\Finance\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Spatie\NovaTranslatable\Translatable;

class Tax extends Resource
{
    public static $model = \App\Domains\Finance\Models\Tax::class;

    public static $title = 'name';

    public static $globallySearchable = false;

    public static $search = [
        'name',
    ];

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Translatable::make([
                Text::make('Name'),
            ]),

            HasMany::make('Rates', 'rates', TaxRate::class),
        ];
    }
}
