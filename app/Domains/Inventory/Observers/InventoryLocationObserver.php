<?php

namespace App\Domains\Inventory\Observers;

use App\Domains\Inventory\Models\Enums\InventoryAreaType;
use App\Domains\Inventory\Models\InventoryArea;
use App\Domains\Inventory\Models\InventoryLocation;
use Illuminate\Support\Str;

class InventoryLocationObserver
{
    public function created(InventoryLocation $location): void
    {
        /** @var InventoryArea $area */
        $area = $location->areas()->make();

        $area->type = InventoryAreaType::initial();
        $area->setTranslations('name', [
            'ar' => 'الرصيد الافتتاحي',
            'en' => 'Initial Balance',
        ]);

        $area->save();
    }

    public function updating(InventoryLocation $location): void
    {
        $location->getConnection()->transaction(function () use ($location): void {
            if ($location->wasChanged('code')) {
                $location->areas->each(function (InventoryArea $area) use ($location): void {
                    $area->code = Str::replaceFirst(
                        $location->getOriginal('code'),
                        $location->code,
                        $area->code,
                    );
                    $area->save();
                });
            }
        }, 5);
    }
}
