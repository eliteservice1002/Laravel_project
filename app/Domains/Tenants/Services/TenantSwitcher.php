<?php

namespace App\Domains\Tenants\Services;

use App\Domains\Tenants\Events\TenantSet;
use App\Domains\Tenants\Events\TenantUnset;
use App\Domains\Tenants\Models\Tenant;
use Illuminate\Events\Dispatcher;

class TenantSwitcher
{
    protected Tenant $current;

    public function __construct(protected Dispatcher $dispatcher)
    {
        $this->switch(new Tenant());
    }

    public function current(): Tenant
    {
        return $this->current;
    }

    public function switch(Tenant $tenant): void
    {
        $this->current = $tenant;

        if ($tenant->exists) {
            $this->dispatcher->dispatch(new TenantSet($tenant));
        } else {
            $this->dispatcher->dispatch(new TenantUnset());
        }
    }

    public function executeFor(Tenant $tenant, callable $callable): void
    {
        $original = $this->current;

        $this->switch($tenant);

        try {
            $callable($tenant, $this);
        } catch (\Throwable $exception) {
            throw $exception;
        } finally {
            $this->switch($original);
        }
    }
}
