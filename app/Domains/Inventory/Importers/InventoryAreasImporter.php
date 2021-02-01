<?php

namespace App\Domains\Inventory\Importers;

use App\Domains\Inventory\Models\Enums\InventoryAreaType;
use App\Domains\Inventory\Models\InventoryArea;
use App\Domains\Inventory\Models\InventoryLocation;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Spatie\Enum\Laravel\Rules\EnumRule;

class InventoryAreasImporter implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    protected static array $inventoryLocationsMap = [];

    public function collection(Collection $collection): void
    {
        $collection->each(function (Collection $row): void {
            /** @var InventoryArea $inventoryArea */
            $inventoryArea = InventoryArea::query()
                ->firstOrNew(['code' => $row['code']]);

            $inventoryArea->type = InventoryAreaType::{$row['type']}();
            $inventoryArea->inventory_location_id = $this->getInventoryLocationId($row['inventory_location']);
            $inventoryArea->setTranslation('name', 'ar', $row['name_ar']);
            if (isset($row['name_en'])) {
                $inventoryArea->setTranslation('name', 'en', $row['name_en']);
            }

            $inventoryArea->save();
        });
    }

    public function rules(): array
    {
        return [
            'code' => 'required',
            'inventory_location' => [
                'required',
                Rule::exists('inventory_locations', 'code'),
            ],
            'type' => [
                'required',
                new EnumRule(InventoryAreaType::class),
            ],
            'name_ar' => 'required|string',
            'name_en' => 'string',
        ];
    }

    protected function getInventoryLocationId(string $inventoryLocationCode): int
    {
        if ( ! isset(static::$inventoryLocationsMap[$inventoryLocationCode])) {
            static::$inventoryLocationsMap[$inventoryLocationCode] = InventoryLocation::query()
                ->where('code', $inventoryLocationCode)
                ->firstOrFail();
        }

        return static::$inventoryLocationsMap[$inventoryLocationCode]->getKey();
    }
}
