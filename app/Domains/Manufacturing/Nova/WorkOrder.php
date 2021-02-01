<?php

namespace App\Domains\Manufacturing\Nova;

use App\Domains\Inventory\Nova\InventoryArea;
use App\Domains\Vendors\Nova\Vendor;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;

class WorkOrder extends Resource
{
    public static $model = \App\Domains\Manufacturing\Models\WorkOrder::class;

    public static $title = 'code';

    public static $search = [
        'code',
    ];

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Number::make('Code')
                ->exceptOnForms(),

            BelongsTo::make('Vendor', 'vendor', Vendor::class),

            BelongsTo::make('Delivery Area', 'deliveryArea', InventoryArea::class),

            HasMany::make('Items', 'workOrderItems', WorkOrderItem::class),
        ];
    }
}
