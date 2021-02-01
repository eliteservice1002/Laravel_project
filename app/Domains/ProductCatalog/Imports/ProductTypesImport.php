<?php

namespace App\Domains\ProductCatalog\Imports;

use App\Domains\ProductCatalog\Models\ProductType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductTypesImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public function collection(Collection $collection): void
    {
        $collection->each(function (Collection $row): void {
            /** @var ProductType $productType */
            $productType = ProductType::query()->firstOrNew(['code' => $row['code']]);

            $productType->setTranslation('name', 'ar', $row['name_ar']);
            if (isset($row['name_en'])) {
                $productType->setTranslation('name', 'en', $row['name_en']);
            }

            $productType->save();
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
        ];
    }
}
