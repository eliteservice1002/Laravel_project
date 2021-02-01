<?php

namespace Database\Seeders;

use App\Domains\Tenants\Models\Store;
use App\Domains\Tenants\Models\Tenant;
use Illuminate\Database\Seeder;

class CoreSeeder extends Seeder
{
    public function run(): void
    {
        /** @var Tenant $tenant */
        $tenant = Tenant::query()->updateOrCreate(['id' => 1], [
            'domain' => 'johrh.'.config('app.domain'),
            'name' => [
                'en' => 'Etlalat Johrh Company',
                'ar' => 'شركة إطلالة جوهرة',
            ],
        ]);

        Store::query()
            ->updateOrCreate(['ulid' => '01EVBV3BQRXMTZENMXH86GTG6S'], [
                'tenant_id' => $tenant->getKey(),
                'code' => 'JOHRH',
                'name' => [
                    'en' => 'Johrh Store',
                    'ar' => 'متجر جوهرة',
                ],
                'domain' => 'johrh-store.'.config('app.domain'),
            ]);
    }
}
