<?php

namespace App\Domains\Accounting\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class AccountingCurrency extends Resource
{
    public static $model = \App\Domains\Accounting\Models\AccountingCurrency::class;

    public static $title = 'code';

    public function fields(Request $request): array
    {
        return [
            Text::make('Code')->sortable(),

            Text::make('id'),
        ];
    }
}
