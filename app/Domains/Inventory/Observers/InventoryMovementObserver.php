<?php

namespace App\Domains\Inventory\Observers;

use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Inventory\Models\InventoryMovement;

class InventoryMovementObserver
{
    public function created(InventoryMovement $movement): void
    {
        $movement->area->productItems()->attach($movement->productItem);

        /** @var InventoryItem $inventoryItem */
        $inventoryItem = $movement->area->inventoryItems()
            ->where('product_id', $movement->productItem->id)
            ->firstOrFail();

        $inventoryItem->stock ??= 0;
        $inventoryItem->stock += $movement->quantity;

        $inventoryItem->save();
    }
}
