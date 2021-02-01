<?php

namespace App\Domains\Inventory\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Spatie\NovaTranslatable\Translatable;

class InventoryLocation extends Resource
{
    public static $model = \App\Domains\Inventory\Models\InventoryLocation::class;

    public static $title = 'name';

    public static $search = [
        'name',
        'code',
    ];

    public static function label()
    {
        return 'Locations';
    }

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Translatable::make([
                Text::make('Name'),
            ]),

            Text::make('Code'),

            HasMany::make('Areas', 'areas', InventoryArea::class),
        ];
    }
}
