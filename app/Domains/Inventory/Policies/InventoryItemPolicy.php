<?php

namespace App\Domains\Inventory\Policies;

use App\Domains\Core\Models\CoreUser;
use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryItemPolicy
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

    public function view(TenantUser $user, InventoryItem $inventoryItem): bool
    {
        return true;
    }

    public function create(TenantUser $user): bool
    {
        return true;
    }

    public function update(TenantUser $user, InventoryItem $inventoryItem): bool
    {
        return true;
    }

    public function delete(TenantUser $user, InventoryItem $inventoryItem): bool
    {
        return true;
    }

    public function restore(TenantUser $user, InventoryItem $inventoryItem): bool
    {
        return true;
    }

    public function addTransferOrder(TenantUser $user, InventoryItem $inventoryItem): bool
    {
        return true;
    }
}
