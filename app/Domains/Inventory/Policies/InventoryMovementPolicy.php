<?php

namespace App\Domains\Inventory\Policies;

use App\Domains\Core\Models\CoreUser;
use App\Domains\Inventory\Models\InventoryMovement;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryMovementPolicy
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

    public function view(TenantUser $user, InventoryMovement $movement): bool
    {
        return true;
    }

    public function create(TenantUser $user): bool
    {
        return false;
    }

    public function update(TenantUser $user, InventoryMovement $movement): bool
    {
        return false;
    }

    public function delete(TenantUser $user, InventoryMovement $movement): bool
    {
        return false;
    }

    public function restore(TenantUser $user, InventoryMovement $movement): bool
    {
        return false;
    }

    public function addTransferOrder(TenantUser $user, InventoryMovement $movement): bool
    {
        return false;
    }
}
