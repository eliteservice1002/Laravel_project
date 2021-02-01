<?php

namespace App\Domains\Purchasing\Nova;

use App\Domains\Core\Nova\Fields\Money;
use App\Domains\Core\Nova\Fields\ProgressBar;
use App\Domains\Inventory\Models\Enums\InventoryAreaType;
use App\Domains\Inventory\Nova\InventoryArea;
use App\Domains\Purchasing\States\PurchaseOrderState\Transitions\DraftToIssued;
use App\Domains\Vendors\Nova\Vendor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;

class PurchaseOrder extends Resource
{
    public static $model = \App\Domains\Purchasing\Models\PurchaseOrder::class;

    public static $title = 'code';

    public static $search = [
        'code',
    ];

    public static function relatableInventoryAreas(NovaRequest $request, Builder $query): Builder
    {
        return $query->where('type', InventoryAreaType::receiving());
    }

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Number::make('Code')
                ->exceptOnForms(),

            Badge::make('State')
                ->resolveUsing(fn ($value) => (string) $value)
                ->map([
                    'draft' => 'warning',
                    'issued' => 'info',
                    'closed' => 'success',
                    'cancelled' => 'warning',
                ]),

            ProgressBar::make('Delivery')
                ->resolveUsing(fn () => 0)
                ->exceptOnForms(),

            BelongsTo::make('Vendor', 'vendor', Vendor::class)
                ->searchable(),

            Date::make('Issue Date')
                ->hideWhenCreating()
                ->default(Carbon::now()),

            Date::make('Delivery Date')
                ->required()
                ->hideFromIndex(),

            BelongsTo::make('Delivery Area', 'deliveryArea', InventoryArea::class),

            Money::make('Total Amount', function (\App\Domains\Purchasing\Models\PurchaseOrder $order) {
                return $order->lineItems
                    ->reduce(function ($c, \App\Domains\Purchasing\Models\PurchaseOrderLineItem $item) {
                        return $item->unit_price->multipliedBy($item->quantity)->plus($c);
                    }, \Brick\Money\Money::zero('SAR'));
            })
                ->currency('SAR')
                ->exceptOnForms(),

            HasMany::make('Line Items', 'lineItems', PurchaseOrderLineItem::class),
        ];
    }

    public function actions(Request $request)
    {
        return array_merge([
            new class() extends Action {
                public $name = 'Issue Purchase Order';

                public $confirmButtonText = 'Isseu PO';

                public function handle(ActionFields $fields, Collection $orders): array
                {
                    foreach ($orders as $order) {
                        /** @var \App\Domains\Purchasing\Models\PurchaseOrder $order */
                        try {
                            $order->state->transition(new DraftToIssued($order, $fields->get('issue_date')));
                        } catch (CouldNotPerformTransition $e) {
                        }
                    }

                    return Action::message('PO issued!');
                }

                public function fields(): array
                {
                    return [
                        Date::make('Issue Date')
                            ->default(Carbon::now()),
                    ];
                }
            },
        ], parent::actions($request));
    }

    public function filters(Request $request): array
    {
        return array_merge([
            new class() extends DateFilter {
                public $name = 'Issue Date';

                public function apply(Request $request, $query, $value)
                {
                    return $query->where('issue_date', '=', Carbon::parse($value));
                }
            },
        ], parent::filters($request));
    }
}
