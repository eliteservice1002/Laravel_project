<?php

namespace App\Domains\Tenants\Observers;

use App\Domains\Tenants\Models\Tenant;

class TenantObserver
{
    public function created(Tenant $tenant): void
    {
        $tenant->getConnection()
            ->statement("CREATE SCHEMA IF NOT EXISTS {$tenant->getSchema()};");
    }
}
