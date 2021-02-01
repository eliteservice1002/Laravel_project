<?php

namespace App\Domains\Tenants\Policies;

use App\Domains\Core\Models\CoreUser;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantUserPolicy
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

    public function view(TenantUser $user, TenantUser $tenantUser): bool
    {
        return true;
    }

    public function create(TenantUser $user): bool
    {
        return false;
    }

    public function update(TenantUser $user, TenantUser $tenantUser): bool
    {
        return true;
    }

    public function delete(TenantUser $user, TenantUser $tenantUser): bool
    {
        return true;
    }

    public function restore(TenantUser $user, TenantUser $tenantUser): bool
    {
        return true;
    }
}
