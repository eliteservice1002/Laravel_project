<?php

namespace App\Domains\Vendors\Observers;

use App\Domains\Inventory\Models\Enums\InventoryAreaType;
use App\Domains\Inventory\Models\InventoryArea;
use App\Domains\Inventory\Models\InventoryLocation;
use App\Domains\Vendors\Models\Vendor;

class VendorObserver
{
    public function saved(Vendor $vendor): void
    {
        /** @var InventoryLocation $location */
        $location = InventoryLocation::query()
            ->updateOrCreate(['code' => $vendor->code], [
                'name' => [
                    'ar' => '['.$vendor->code.'] '.$vendor->getTranslation('name', 'ar'),
                    'en' => '['.$vendor->code.'] '.$vendor->getTranslation('name', 'en'),
                ],
            ]);

        /** @var InventoryArea $area */
        $area = $location->areas()
            ->updateOrCreate(['code' => $vendor->getPurchaseOrdersAreaCode()], [
                'type' => InventoryAreaType::purchase_orders(),
                'name' => [
                    'ar' => $location->getTranslation('name', 'ar').' - المشتريات',
                    'en' => $location->getTranslation('name', 'ar').' - Purchasing',
                ],
            ]);
    }
}
