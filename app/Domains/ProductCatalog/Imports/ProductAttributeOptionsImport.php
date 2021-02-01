<?php

namespace App\Domains\ProductCatalog\Imports;

use App\Domains\ProductCatalog\Models\ProductAttribute;
use App\Domains\ProductCatalog\Models\ProductAttributeOption;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductAttributeOptionsImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    protected static array $productAttributesMap = [];

    public function collection(Collection $collection): void
    {
        $collection->each(function (Collection $row): void {
            /** @var ProductAttributeOption $productAttributeOption */
            $productAttributeOption = ProductAttributeOption::query()
                ->firstOrNew(['code' => $row['code']]);

            $productAttributeOption->product_attribute_id = $this->getProductAttributeId($row['product_attribute']);

            $productAttributeOption->setTranslation('name', 'ar', $row['name_ar']);
            if (isset($row['name_en'])) {
                $productAttributeOption->setTranslation('name', 'en', $row['name_en']);
            }

            $productAttributeOption->save();
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
            'product_attribute' => [
                'required',
                Rule::exists('product_attributes', 'code'),
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

    protected function getProductAttributeId(string $productAttributeCode): int
    {
        if ( ! isset(static::$productAttributesMap[$productAttributeCode])) {
            static::$productAttributesMap[$productAttributeCode] = ProductAttribute::query()
                ->where('code', $productAttributeCode)
                ->firstOrFail();
        }

        return static::$productAttributesMap[$productAttributeCode]->getKey();
    }
}
