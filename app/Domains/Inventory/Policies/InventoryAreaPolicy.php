<?php

namespace App\Domains\Inventory\Policies;

use App\Domains\Core\Models\CoreUser;
use App\Domains\Inventory\Models\InventoryArea;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryAreaPolicy
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

    public function view(TenantUser $user, InventoryArea $area): bool
    {
        return true;
    }

    public function create(TenantUser $user): bool
    {
        return true;
    }

    public function update(TenantUser $user, InventoryArea $area): bool
    {
        return true;
    }

    public function delete(TenantUser $user, InventoryArea $area): bool
    {
        return true;
    }

    public function restore(TenantUser $user, InventoryArea $area): bool
    {
        return true;
    }

    public function addTransferOrder(TenantUser $user, InventoryArea $area): bool
    {
        return true;
    }
}
