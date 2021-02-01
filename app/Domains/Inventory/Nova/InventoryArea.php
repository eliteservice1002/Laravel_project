<?php

namespace App\Domains\Inventory\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Spatie\NovaTranslatable\Translatable;

/**
 * @property \App\Domains\Inventory\Models\InventoryArea $resource
 */
class InventoryArea extends Resource
{
    public static $model = \App\Domains\Inventory\Models\InventoryArea::class;

    public static $displayInNavigation = false;

    public static $search = [
        'name',
        'code',
    ];

    public static function label()
    {
        return 'Areas';
    }

    public function title(): string
    {
        return "[{$this->resource->code}] {$this->resource->inventoryLocation->name} - {$this->resource->name}";
    }

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Translatable::make([
                Text::make('Name'),
            ]),

            Text::make('Code'),

            BelongsTo::make('Location', 'location', InventoryLocation::class),

            HasMany::make('Inventory Items', 'inventoryItems', InventoryItem::class),

            // BelongsToMany::make('Products')
            //     ->fields(new InventoryItemFields()),

            HasMany::make('Inventory Movement', 'inventoryMovement', InventoryMovement::class),

            HasMany::make('Originating Transfer Orders', 'originatingTransferOrders', TransferOrder::class),

            HasMany::make('Terminating Transfer Orders', 'terminatingTransferOrders', TransferOrder::class),
        ];
    }
}
