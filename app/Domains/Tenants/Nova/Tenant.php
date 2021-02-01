<?php

namespace App\Domains\Tenants\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Spatie\NovaTranslatable\Translatable;

class Tenant extends Resource
{
    public static $model = \App\Domains\Tenants\Models\Tenant::class;

    public static $displayInNavigation = false;

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
                    ->sortable()
                    ->rules('required', 'max:255'),
            ]),

            Slug::make('slug')->hideWhenUpdating(),
        ];
    }
}
