<?php

namespace App\Domains\ProductCatalog\Imports;

use App\Domains\ProductCatalog\Models\Product;
use App\Domains\ProductCatalog\Models\ProductType;
use App\Domains\ProductCatalog\Models\ProductUnit;
use App\Domains\Tenants\Models\Store;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    protected static array $storesMap = [];

    protected static array $productTypesMap = [];

    protected static array $productUnitsMap = [];

    public function collection(Collection $collection): void
    {
        $collection->each(function (Collection $row): void {
            /** @var Product $product */
            $product = Product::query()->firstOrNew(['code' => $row['code']]);

            $product->store_id = $this->getStoreId($row['store']);
            $product->product_type_id = $this->getProductTypeId($row['product_type']);
            $product->product_unit_id = $this->getProductUnitId($row['product_unit']);

            $product->setTranslation('name', 'ar', $row['name_ar']);
            if (isset($row['name_en'])) {
                $product->setTranslation('name', 'en', $row['name_en']);
            }

            $product->save();
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
            'store' => [
                'required',
                Rule::exists(Store::class, 'code'),
            ],
            'product_type' => [
                'required',
                Rule::exists(ProductType::class, 'code'),
            ],
            'product_unit' => [
                'required',
                Rule::exists(ProductUnit::class, 'code'),
            ],
            'name_ar' => [
                'required',
                'string',
            ],
            'name_en' => [
                'string',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }

    protected function getStoreId(string $storeCode): int
    {
        if ( ! isset(static::$storesMap[$storeCode])) {
            static::$storesMap[$storeCode] = Store::query()
                ->where('code', $storeCode)
                ->firstOrFail();
        }

        return static::$storesMap[$storeCode]->getKey();
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

    protected function getProductUnitId(string $productUnitCode): int
    {
        if ( ! isset(static::$productUnitsMap[$productUnitCode])) {
            static::$productUnitsMap[$productUnitCode] = ProductUnit::query()
                ->where('code', $productUnitCode)
                ->firstOrFail();
        }

        return static::$productUnitsMap[$productUnitCode]->getKey();
    }
}
