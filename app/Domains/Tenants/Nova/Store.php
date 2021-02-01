<?php

namespace App\Domains\Tenants\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Spatie\NovaTranslatable\Translatable;

class Store extends Resource
{
    public static $model = \App\Domains\Tenants\Models\Store::class;

    public static $title = 'name';

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
        ];
    }
}
