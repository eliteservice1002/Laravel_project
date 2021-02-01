<?php

namespace App\Domains\ProductCatalog\Imports;

use App\Domains\ProductCatalog\Models\ProductAttribute;
use App\Domains\ProductCatalog\Models\ProductType;
use App\Domains\ProductCatalog\Models\ProductTypeAttributeAssociation;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductTypeAttributeAssociationsImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    protected static array $productTypesMap = [];

    protected static array $productAttributesMap = [];

    public function collection(Collection $collection): void
    {
        $collection->each(function (Collection $row): void {
            ProductTypeAttributeAssociation::query()
                ->firstOrCreate([
                    'product_type_id' => $this->getProductTypeId($row['product_type']),
                    'product_attribute_id' => $this->getProductAttributeId($row['product_attribute']),
                ]);
        });
    }

    public function rules(): array
    {
        return [
            'product_type' => [
                'required',
                Rule::exists('product_types', 'code'),
            ],
            'product_attribute' => [
                'required',
                Rule::exists('product_attributes', 'code'),
            ],
        ];
    }

    protected function getProductTypeId(string $productTypeCode): int
    {
        if ( ! isset(static::$productTypesMap[$productTypeCode])) {
            static::$productTypesMap[$productTypeCode] = ProductType::query()
                ->where('code', $productTypeCode)
                ->firstOrFail();
        }

        return static::$productTypesMap[$productTypeCode]->getKey();
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
