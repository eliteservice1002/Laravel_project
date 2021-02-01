<?php

namespace App\Domains\Tenants\Policies;

use App\Domains\Core\Models\CoreUser;
use App\Domains\Tenants\Models\Tenant;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantPolicy
{
    use HandlesAuthorization;

    public function before(CoreUser $user, $ability): ?bool
    {
        if ( ! $user instanceof TenantUser) {
            return false;
        }

        return null;
    }

    public function viewAny(TenantUser $user): bool
    {
        return true;
    }

    public function view(TenantUser $user, Tenant $tenant): bool
    {
        return true;
    }

    public function create(TenantUser $user): bool
    {
        return false;
    }

    public function update(TenantUser $user, Tenant $tenant): bool
    {
        return true;
    }

    public function delete(TenantUser $user, Tenant $tenant): bool
    {
        return true;
    }

    public function restore(TenantUser $user, Tenant $tenant): bool
    {
        return true;
    }

    public function addInventoryLocation(TenantUser $user, Tenant $tenant): bool
    {
        return true;
    }

    public function addInventoryArea(TenantUser $user, Tenant $tenant): bool
    {
        return true;
    }

    public function addTransferOrder(TenantUser $user, Tenant $tenant): bool
    {
        return true;
    }
}
