<?php

namespace App\Domains\Vendors\Nova;

use App\Domains\Purchasing\Nova\PurchaseInvoice;
use App\Domains\Purchasing\Nova\PurchaseOrder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;

/**
 * @property \App\Domains\Vendors\Models\Vendor $resource
 */
class Vendor extends Resource
{
    public static $model = \App\Domains\Vendors\Models\Vendor::class;

    public static $title = 'name';

    public static $search = [
        'name',
        'code',
    ];

    public function title(): string
    {
        return "[{$this->resource->code}] {$this->resource->name}";
    }

    public function fields(Request $request): array
    {
        return [
            Text::make('Code')
                ->sortable()
                ->readonly(),

            Text::make('Name')
                ->required(),

            Text::make('Company Name')
                ->required(),

            Text::make('VAT Account Number'),

            HasMany::make('Vendor Users', 'vendorUsers', VendorUser::class),

            HasMany::make('Purchase Orders', 'purchaseOrders', PurchaseOrder::class)
                ->readonly(),

            HasMany::make('Purchase Invoices', 'purchaseInvoices', PurchaseInvoice::class)
                ->readonly(),
        ];
    }
}
