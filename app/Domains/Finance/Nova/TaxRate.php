<?php

namespace App\Domains\Finance\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Nsavinov\NovaPercentField\Percent;

class TaxRate extends Resource
{
    public static $model = \App\Domains\Finance\Models\TaxRate::class;

    public static $title = 'name';

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make('Tax', 'tax', Tax::class),

            Percent::make('Percentage')->precision(2),

            Date::make('Effective Since'),

            Date::make('Effective Until'),
        ];
    }
}
