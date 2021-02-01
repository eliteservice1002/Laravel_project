<?php

namespace App\Domains\ProductCatalog\Imports;

use App\Domains\ProductCatalog\Models\Product;
use App\Domains\ProductCatalog\Models\ProductAttribute;
use App\Domains\ProductCatalog\Models\ProductItem;
use App\Domains\ProductCatalog\Models\ProductUnit;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductItemsImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    protected static array $productsMap = [];

    protected static array $productUnitsMap = [];

    protected static array $productAttributesMap = [];

    public function collection(Collection $collection): void
    {
        $collection->each(function (Collection $row) {
            /** @var ProductItem $productItem */
            $productItem = ProductItem::query()
                ->firstOrNew(['code' => $row['code']]);

            $productItem->product_id = $this->getProductId($row['product']);
            $productItem->product_unit_id = $this->getProductUnitId($row['product_unit']);

            foreach ($row->get('product_attributes') as $productAttributeCode) {
                $productAttribute = $this->getProductAttributeId($productAttributeCode);
            }

            $productItem->save();
        });
    }

    public function prepareForValidation($data, $index): array
    {
        $data['product_attributes'] = collect($data)
            ->keys()
            ->filter(fn ($k) => str_contains($k, 'product_attribute_'))
            ->map(fn ($k) => Str::afterLast($k, 'product_attribute_'))
            ->values()
            ->all();

        return $data;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
            ],
            'product' => [
                'required',
                Rule::exists('products', 'code'),
            ],
            'product_unit' => [
                'required',
                Rule::exists('product_units', 'code'),
            ],
            'product_attributes' => [
                'required',
            ],
        ];
    }

    protected function getProductId(string $productCode): int
    {
        if ( ! isset(static::$productsMap[$productCode])) {
            static::$productsMap[$productCode] = Product::query()
                ->where('code', $productCode)
                ->firstOrFail();
        }

        return static::$productsMap[$productCode]->getKey();
    }

    protected function getProductUnitId(string $productUnitCode): int
    {
        if ( ! isset(static::$productUnitsMap[$productUnitCode])) {
            static::$productUnitsMap[$productUnitCode] = ProductUnit::query()
                ->where('code', $productUnitCode)
                ->firstOrFail();
        }

        return static::$productUnitsMap[$productUnitCode]->getKey();
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
