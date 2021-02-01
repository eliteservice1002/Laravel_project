<?php

namespace App\Domains\Vendors\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;

/**
 * @property \App\Domains\Vendors\Models\VendorUser $resource
 */
class VendorUser extends Resource
{
    public static $model = \App\Domains\Vendors\Models\VendorUser::class;

    public static $title = 'name';

    public static $search = [
        'code',
        'name',
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

            Text::make('Email')
                ->required(),

            Text::make('Mobile')
                ->required(),

            BelongsTo::make('Vendor', 'vendor', Vendor::class),
        ];
    }
}
