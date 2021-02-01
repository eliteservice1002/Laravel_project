<?php

namespace App\Domains\Vendors\Imports;

use App\Domains\Vendors\Models\Vendor;
use App\Domains\Vendors\Models\VendorUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class VendorUsersImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    protected static array $vendorsMap = [];

    public function collection(Collection $collection): void
    {
        $collection->each(function (Collection $row) {
            /** @var VendorUser $vendorUser */
            $vendorUser = VendorUser::query()->firstOrNew(['code' => $row['code']]);

            $vendorUser->vendor_id = $this->getVendorId($row['vendor']);

            $vendorUser->setTranslation('name', 'ar', $row['name_ar']);
            if (isset($row['name_en'])) {
                $vendorUser->setTranslation('name', 'en', $row['name_en']);
            }

            $vendorUser->mobile = $row['mobile'] ?? null;
            $vendorUser->email = $row['email'] ?? null;
            $vendorUser->password = bcrypt(Str::random(64));

            $vendorUser->save();
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
            'vendor' => [
                'required',
            ],
            'name' => [
                'required',
            ],
            'email' => [
                'nullable',
                'string',
            ],
            'mobile' => [
                'nullable',
                'numeric',
            ],
        ];
    }

    protected function getVendorId(string $vendorCode): int
    {
        if ( ! isset(static::$vendorsMap[$vendorCode])) {
            static::$vendorsMap[$vendorCode] = Vendor::query()
                ->where('code', $vendorCode)
                ->firstOrFail();
        }

        return static::$vendorsMap[$vendorCode]->getKey();
    }
}
