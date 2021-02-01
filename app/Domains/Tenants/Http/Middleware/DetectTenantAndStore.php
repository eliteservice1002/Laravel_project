<?php

namespace App\Domains\Tenants\Http\Middleware;

use App\Domains\Tenants\Models\Store;
use App\Domains\Tenants\Models\Tenant;
use App\Domains\Tenants\Services\StoreSwitcher;
use App\Domains\Tenants\Services\TenantSwitcher;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectTenantAndStore
{
    private const SESSION_TENANT_KEY = 'current_tenant';
    private const SESSION_STORE_KEY = 'current_store';

    public function __construct(
        protected TenantSwitcher $tenantSwitcher,
        protected StoreSwitcher $storeSwitcher,
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        try {
            /** @var Store $store */
            $store = Store::query()->where('domain', $request->getHost())->firstOrFail();

            $this->setStore($request, $store);
        } catch (ModelNotFoundException) {
            try {
                /** @var Tenant $tenant */
                $tenant = Tenant::query()->where('domain', $request->getHost())->firstOrFail();
                $this->setTenant($request, $tenant);
            } catch (ModelNotFoundException) {
                abort(Response::HTTP_NOT_FOUND);
            }
        }

        return $next($request);
    }

    protected function setTenant(Request $request, Tenant $tenant): void
    {
        $this->tenantSwitcher->switch($tenant);
        $request->merge(['currentTenant' => $tenant]);
    }

    protected function setStore(Request $request, Store $store): void
    {
        $this->storeSwitcher->switch($store);
        $request->merge(['currentStore' => $store]);
    }

    protected function storeSession(Request $request): void
    {
        // TODO(ibrasho): Deal with storing in store
        // if ($request->hasSession()) {
        //     $sessionTenant = $request->session()->get(self::SESSION_TENANT_KEY);
        //
        //     if (is_null($sessionTenant)) {
        //         $request->session()->put(self::SESSION_TENANT_KEY, $tenant);
        //     } elseif ( ! $sessionTenant->is($tenant)) {
        //         abort(Response::HTTP_UNAUTHORIZED);
        //     }
        //
        //     if ($store) {
        //         $sessionStore = $request->session()->get(self::SESSION_STORE_KEY);
        //
        //         if (is_null($sessionStore)) {
        //             $request->session()->put(self::SESSION_STORE_KEY, $store);
        //         } elseif ( ! $sessionStore->is($store)) {
        //             abort(Response::HTTP_UNAUTHORIZED);
        //         }
        //     }
        // }
    }
}
