<?php

namespace App\Domains\Inventory\Nova;

use App\Domains\Inventory\Nova\Actions\IssueTransferOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class TransferOrder extends Resource
{
    public static $model = \App\Domains\Inventory\Models\TransferOrder::class;

    public static $title = 'code';

    public static $search = [
        'code',
    ];

    public static function label()
    {
        return 'Transfer Orders';
    }

    public static function relatableInventoryAreas(NovaRequest $request, Builder $query): Builder
    {
        return $query;
    }

    public function fields(Request $request): array
    {
        return [
            Number::make('Code')
                ->exceptOnForms(),

            DateTime::make('Issue Date')
                ->exceptOnForms(),

            BelongsTo::make('Source Area', 'sourceArea', InventoryArea::class)
                ->searchable()
                ->rules(['different:targetArea']),

            BelongsTo::make('Target Area', 'targetArea', InventoryArea::class)
                ->searchable()
                ->rules(['different:sourceArea']),

            Badge::make('State')
                ->resolveUsing(fn ($value) => (string) $value)
                ->map([
                    'draft' => 'warning',
                    'issued' => 'info',
                    'closed' => 'success',
                    'cancelled' => 'warning',
                ]),

            HasMany::make('Line Items', 'lineItems', TransferOrderLineItem::class),
        ];
    }

    public function actions(Request $request): array
    {
        return array_merge([
            new IssueTransferOrder(),
        ], parent::actions($request));
    }
}
