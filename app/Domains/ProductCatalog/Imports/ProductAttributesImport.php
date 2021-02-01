<?php

namespace App\Domains\ProductCatalog\Imports;

use App\Domains\ProductCatalog\Models\ProductAttribute;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductAttributesImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public function collection(Collection $collection): void
    {
        $collection->each(function (Collection $row): void {
            /** @var ProductAttribute $productAttribute */
            $productAttribute = ProductAttribute::query()
                ->firstOrNew(['code' => $row['code']]);

            $productAttribute->setTranslation('name', 'ar', $row['name_ar']);
            if (isset($row['name_en'])) {
                $productAttribute->setTranslation('name', 'en', $row['name_en']);
            }

            $productAttribute->save();
        });
    }

    public function prepareForValidation($data, $index): array
    {
        $data['name_ar'] = (string) Arr::get($data, 'name_ar', '');
        $data['name_en'] = (string) Arr::get($data, 'name_en', '');

        return $data;
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
