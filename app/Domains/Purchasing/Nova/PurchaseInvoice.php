<?php

namespace App\Domains\Purchasing\Nova;

use App\Domains\Vendors\Nova\Vendor;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Spatie\NovaTranslatable\Translatable;

class PurchaseInvoice extends Resource
{
    public static $model = \App\Domains\Purchasing\Models\PurchaseInvoice::class;

    public static $title = 'code';

    public static $search = [
        'code',
    ];

    public function fields(Request $request): array
    {
        return [
            Number::make('Code')
                ->exceptOnForms(),

            Translatable::make([
            ]),

            BelongsTo::make('Vendor', 'vendor', Vendor::class),

            HasMany::make('Line Items', 'lineItems', PurchaseInvoiceItem::class),
        ];
    }
}
