<?php

namespace App\Domains\Purchasing\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Spatie\NovaTranslatable\Translatable;

class PurchasePaymentTerm extends Resource
{
    public static $model = \App\Domains\Purchasing\Models\PurchasePaymentTerm::class;

    public static $title = 'name';

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
