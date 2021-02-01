<?php

namespace App\Domains\Inventory\Importers;

use App\Domains\Inventory\Models\InventoryLocation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class InventoryLocationsImporter implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public function collection(Collection $collection): void
    {
        $collection->each(function (Collection $row): void {
            /** @var InventoryLocation $inventoryLocation */
            $inventoryLocation = InventoryLocation::query()
                ->firstOrNew(['code' => $row['code']]);

            $inventoryLocation->setTranslation('name', 'ar', $row['name_ar']);
            if (isset($row['name_en'])) {
                $inventoryLocation->setTranslation('name', 'en', $row['name_en']);
            }

            $inventoryLocation->save();
        });
    }

    public function rules(): array
    {
        return [
            'code' => 'required',
            'name_ar' => 'required|string',
            'name_en' => 'string',
        ];
    }
}
