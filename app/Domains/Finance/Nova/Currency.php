<?php

namespace App\Domains\Finance\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Spatie\NovaTranslatable\Translatable;

class Currency extends Resource
{
    public static $model = \App\Domains\Finance\Models\Currency::class;

    public static $title = 'code';

    public function fields(Request $request): array
    {
        return [
            Text::make('Code')->sortable(),

            Translatable::make([
                Text::make('Name'),

                Text::make('Symbol'),
            ]),
        ];
    }
}
