<?php

namespace App\Domains\ProductCatalog\Imports;

use App\Domains\ProductCatalog\Models\ProductUnit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductUnitsImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public function collection(Collection $collection): void
    {
        $collection->each(function (Collection $row): void {
            /** @var ProductUnit $productUnit */
            $productUnit = ProductUnit::query()
                ->firstOrNew(['code' => $row['code']]);

            $productUnit->setTranslation('name', 'ar', $row['name_ar']);
            if (isset($row['name_en'])) {
                $productUnit->setTranslation('name', 'en', $row['name_en']);
            }

            $productUnit->setTranslation('symbol', 'ar', $row['symbol_ar']);
            if (isset($row['symbol_en'])) {
                $productUnit->setTranslation('symbol', 'en', $row['symbol_en']);
            }

            $productUnit->save();
        });
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
            ],
            'name_ar' => [
                'required',
                'string',
            ],
            'name_en' => [
                'string',
            ],
            'symbol_ar' => [
                'required',
                'string',
            ],
            'symbol_en' => [
                'string',
            ],
        ];
    }
}
