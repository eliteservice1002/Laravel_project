<?php

namespace App\Domains\Inventory\Nova;

use App\Domains\ProductCatalog\Nova\ProductItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;

/**
 * @property \App\Domains\Inventory\Models\TransferOrderLineItem $resource
 */
class TransferOrderLineItem extends Resource
{
    public static $model = \App\Domains\Inventory\Models\TransferOrderLineItem::class;

    public static $displayInNavigation = false;

    public static $searchRelations = [
        'product' => [
            'name',
        ],
    ];

    public function title(): string
    {
        return "[{$this->resource->productItem->code}] {$this->resource->productItem->product->name} ({$this->resource->quantity} {$this->resource->productItem->unit->symbol})";
    }

    public function fields(Request $request): array
    {
        return [
            BelongsTo::make('Transfer Order', 'transferOrder', TransferOrder::class)
                ->exceptOnForms(),

            BelongsTo::make('Product Item', 'productItem', ProductItem::class)
                ->searchable()
                ->creationRules([
                    Rule::unique(static::$model, 'product_item_id')
                        ->where('transfer_order_id',
                            $request->get('viaResource') === 'transfer-orders'
                                ? $request->get('viaResourceId')
                                : null),
                ]),

            Number::make('Quantity')
                ->default(0)
                ->rules([
                    'min:0',
                    function ($attribute, $value, $fail) use ($request) {
                        /** @var \App\Domains\Inventory\Models\TransferOrder $transferOrder */
                        $transferOrder = \App\Domains\Inventory\Models\TransferOrder::query()
                            ->findOrFail($request->get('viaResourceId'));

                        /** @var \App\Domains\ProductCatalog\Models\ProductItem $productItem */
                        $productItem = \App\Domains\ProductCatalog\Models\ProductItem::query()
                            ->find($request->get('productItem'));

                        if (is_null($productItem)) {
                            $fail('No product selected.');
                        }

                        /** @var \App\Domains\Inventory\Models\InventoryItem $inventoryItem */
                        $inventoryItem = $transferOrder->sourceArea->inventoryItems()
                            ->findOrNew($productItem->id);

                        if ($inventoryItem->stock < intval($value)) {
                            $fail("Quantity is more than available. Available quantity is {$inventoryItem->stock}.");
                        }
                    },
                ]),

            Number::make('Source Stock',
                function (\App\Domains\Inventory\Models\TransferOrderLineItem $lineItem) {
                    /** @var \App\Domains\Inventory\Models\InventoryItem $inventoryItem */
                    $inventoryItem = $lineItem->transferOrder->sourceArea->inventoryItems()
                        ->firstWhere('product_item_id', $lineItem->productItem->id);

                    return optional($inventoryItem)->stock ?? 0;
                })
                ->exceptOnForms()
                ->rules([]),

            Number::make('Target Stock',
                function (\App\Domains\Inventory\Models\TransferOrderLineItem $lineItem) {
                    /** @var \App\Domains\Inventory\Models\InventoryItem $inventoryItem */
                    $inventoryItem = $lineItem->transferOrder->targetArea->inventoryItems()
                        ->firstWhere('product_item_id', $lineItem->productItem->id);

                    return optional($inventoryItem)->stock ?? 0;
                })
                ->exceptOnForms()
                ->rules([]),
        ];
    }
}
