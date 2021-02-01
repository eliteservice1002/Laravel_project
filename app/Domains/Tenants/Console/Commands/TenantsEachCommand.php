<?php

namespace App\Domains\Tenants\Console\Commands;

use App\Domains\Tenants\Models\Tenant;
use App\Domains\Tenants\Services\TenantSwitcher;
use Illuminate\Config\Repository;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class TenantsEachCommand extends Command
{
    protected $signature = 'tenants:each {artisanCommand} {--tenant=*}';

    public function handle(Repository $config, TenantSwitcher $tenantSwitcher): int
    {
        $config->set('database.default', 'tenant');

        if ( ! $command = $this->argument('artisanCommand')) {
            $command = $this->ask('Which command do you want to run for each tenant?');
        }

        $tenants = Arr::wrap($this->option('tenant'));

        $tenantQuery = Tenant::query()->when(( ! blank($tenants)), function ($query) use ($tenants) {
            $query->whereIn('id', Arr::wrap($tenants));
        });

        try {
            if ($tenantQuery->count() === 0) {
                $this->error('No tenant(s) found.');

                return -1;
            }
        } catch (QueryException $e) {
            $this->error('There was an issue with the core database: '.$e->getMessage());

            return -1;
        }

        return $tenantQuery
            ->cursor()
            ->map(fn (Tenant $tenant) => $tenantSwitcher->executeFor($tenant, function (Tenant $tenant) use ($command) {
                $this->line('');
                $this->info("Running command `php artisan {$command}` for tenant `{$tenant->name}` (id: {$tenant->getKey()})...");
                $this->line('---------------------------------------------------------');

                Artisan::call($command, [], $this->output);
            }))
            ->sum();
    }
}
