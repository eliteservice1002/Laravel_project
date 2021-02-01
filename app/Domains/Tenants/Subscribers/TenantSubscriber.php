<?php

namespace App\Domains\Tenants\Subscribers;

use App\Domains\Tenants\Events\TenantSet;
use App\Domains\Tenants\Events\TenantUnset;
use App\Domains\Tenants\Models\Tenant;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\DatabaseManager;
use Laravel\Telescope\Telescope;

class TenantSubscriber
{
    public function __construct(
        protected Container $container,
        protected Repository $config,
        protected CacheManager $cache,
        protected DatabaseManager $db,
    ) {
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(TenantSet::class, static::class.'@handleTenantSet');
        $events->listen(TenantUnset::class, static::class.'@handleTenantUnset');
    }

    public function handleTenantSet(TenantSet $event): void
    {
        $this->container->instance(Tenant::class, $event->tenant);

        $this->updateTenantDbConfig($event->tenant);
        $this->updateTenantCacheConfig($event->tenant);
        $this->updateTenantNovaConfig($event->tenant);
    }

    public function handleTenantUnset(TenantUnset $event): void
    {
        $this->container->instance(Tenant::class, null);

        $this->removeTenantDbConfig();
        $this->removeTenantCacheConfig();
        $this->removeTenantNovaConfig();
    }

    protected function updateTenantDbConfig(Tenant $tenant): void
    {
        $this->db->setDefaultConnection('tenant');
        $this->config->set('database.connections.tenant.schema', $tenant->getSchema());
        Telescope::$runsMigrations = false;
        $this->db->purge('tenant');
    }

    protected function updateTenantCacheConfig(Tenant $tenant): void
    {
        $this->config->set('cache.prefix', 'cache_tenant_'.$tenant->getKey());
        $this->cache->forgetDriver();
    }

    protected function updateTenantNovaConfig(Tenant $tenant): void
    {
        $this->config->set('nova.domain', optional($tenant)->domain);
    }

    protected function removeTenantDbConfig(): void
    {
        $this->db->setDefaultConnection('core');
        $this->config->set('database.connections.tenant.schema');
        Telescope::$runsMigrations = true;
        $this->db->purge('tenant');
    }

    protected function removeTenantCacheConfig(): void
    {
        $this->config->set('cache.prefix', 'cache_core');
        $this->cache->forgetDriver();
    }

    protected function removeTenantNovaConfig(): void
    {
        $this->config->set('nova.domain');
    }
}
