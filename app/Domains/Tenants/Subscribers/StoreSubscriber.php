<?php

namespace App\Domains\Tenants\Subscribers;

use App\Domains\Tenants\Events\StoreSet;
use App\Domains\Tenants\Events\StoreUnset;
use App\Domains\Tenants\Models\Store;
use App\Domains\Tenants\Models\Tenant;
use App\Domains\Tenants\Services\TenantSwitcher;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;

class StoreSubscriber
{
    public function __construct(
        protected Container $container,
        protected Repository $config,
        protected TenantSwitcher $tenantSwitcher,
    ) {
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(StoreSet::class, static::class.'@handleStoreSet');
        $events->listen(StoreUnset::class, static::class.'@handleStoreUnset');
    }

    public function handleStoreSet(StoreSet $event): void
    {
        $this->container->instance(Store::class, $event->store);
        $this->tenantSwitcher->switch($event->store->tenant);
    }

    public function handleStoreUnset(StoreUnset $event): void
    {
        $this->container->instance(Store::class, null);

        $this->tenantSwitcher->switch(new Tenant());
    }
}
