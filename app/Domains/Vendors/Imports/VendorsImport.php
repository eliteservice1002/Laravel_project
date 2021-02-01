<?php

namespace App\Domains\Vendors\Imports;

use App\Domains\Vendors\Models\Vendor;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class VendorsImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public function collection(Collection $collection): void
    {
        $collection->each(function (Collection $row): void {
            /** @var Vendor $vendor */
            $vendor = Vendor::query()->firstOrNew(['code' => $row['code']]);

            $vendor->name = $row['name'];
            $vendor->company_name = $row['company_name'];
            $vendor->vat_account_number = $row['vat_account_number'];

            $vendor->save();
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
            'code' => 'required',
            'name' => 'required',
            'company_name' => 'required',
            'vat_account_number' => 'nullable|numeric',
        ];
    }
}
