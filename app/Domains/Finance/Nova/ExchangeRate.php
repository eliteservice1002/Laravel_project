<?php

namespace App\Domains\Finance\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;

/**
 * @property string $source_currency_code
 * @property string $target_currency_code
 * @property string $exchange_rate
 */
class ExchangeRate extends Resource
{
    public static $model = \App\Domains\Finance\Models\ExchangeRate::class;

    public static $globallySearchable = false;

    public static $search = [
        'source_currency_code',
        'target_currency_code',
    ];

    public function title(): string
    {
        return "{$this->source_currency_code}->{$this->target_currency_code}";
    }

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make('Source Currency', 'sourceCurrency', Currency::class)->searchable(),

            BelongsTo::make('Target Currency', 'targetCurrency', Currency::class)->searchable(),

            Number::make('Exchange Rate'),

            DateTime::make('Active From')->sortable(),
        ];
    }
}
