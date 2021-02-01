<?php

namespace App\Domains\Accounting\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Spatie\NovaTranslatable\Translatable;

/**
 * @property \App\Domains\Accounting\Models\AccountingAccount $resource
 */
class AccountingAccount extends Resource
{
    public static $model = \App\Domains\Accounting\Models\AccountingAccount::class;

    public function title()
    {
        return "[{$this->resource->code}] {$this->resource->name}";
    }

    public function fields(Request $request): array
    {
        return [
            Text::make('Code')->sortable(),

            Translatable::make([
                Text::make('Name'),
            ]),
        ];
    }
}
