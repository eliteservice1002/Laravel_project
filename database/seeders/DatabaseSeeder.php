<?php

namespace Database\Seeders;

use App\Domains\Tenants\Models\Tenant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(Tenant $tenant = null): void
    {
        if ($tenant->exists) {
            $this->call(TenantSeeder::class);
        } else {
            $this->call(CoreSeeder::class);
        }
    }
}
