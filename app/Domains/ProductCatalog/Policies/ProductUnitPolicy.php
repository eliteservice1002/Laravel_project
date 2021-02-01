<?php

namespace App\Domains\ProductCatalog\Policies;

use App\Domains\Core\Models\CoreUser;
use App\Domains\ProductCatalog\Models\ProductUnit;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductUnitPolicy
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

    public function view(TenantUser $user, ProductUnit $unit): bool
    {
        return true;
    }

    public function create(TenantUser $user): bool
    {
        return true;
    }

    public function update(TenantUser $user, ProductUnit $unit): bool
    {
        return true;
    }

    public function delete(TenantUser $user, ProductUnit $unit): bool
    {
        return true;
    }

    public function restore(TenantUser $user, ProductUnit $unit): bool
    {
        return true;
    }

    public function addTransferOrder(TenantUser $user, ProductUnit $unit): bool
    {
        return true;
    }
}
