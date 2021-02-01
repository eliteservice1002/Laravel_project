<?php

namespace App\Domains\Core\Models\Concerns;

trait TenantConnection
{
    public function getConnectionName(): string
    {
        return 'tenant';
    }
}
