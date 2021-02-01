<?php

namespace App\Domains\Inventory\Policies;

use App\Domains\Core\Models\CoreUser;
use App\Domains\Inventory\Models\InventoryLocation;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryLocationPolicy
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

    public function view(TenantUser $user, InventoryLocation $location): bool
    {
        return true;
    }

    public function create(TenantUser $user): bool
    {
        return true;
    }

    public function update(TenantUser $user, InventoryLocation $location): bool
    {
        return true;
    }

    public function delete(TenantUser $user, InventoryLocation $location): bool
    {
        return true;
    }

    public function restore(TenantUser $user, InventoryLocation $location): bool
    {
        return true;
    }

    public function addTransferOrder(TenantUser $user, InventoryLocation $location): bool
    {
        return true;
    }
}
