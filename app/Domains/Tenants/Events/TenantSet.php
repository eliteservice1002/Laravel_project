<?php

namespace App\Domains\Tenants\Events;

use App\Domains\Tenants\Models\Tenant;

class TenantSet
{
    public function __construct(
        public Tenant $tenant,
    ) {
    }
}
