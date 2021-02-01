<?php

namespace App\Domains\Inventory\Policies;

use App\Domains\Core\Models\CoreUser;
use App\Domains\Inventory\Models\TransferOrderLineItem;
use App\Domains\Inventory\States\TransferOrderState\Closed;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransferOrderItemPolicy
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

    public function view(TenantUser $user, TransferOrderLineItem $lineItem): bool
    {
        return true;
    }

    public function create(TenantUser $user): bool
    {
        return true;
    }

    public function update(TenantUser $user, TransferOrderLineItem $lineItem): bool
    {
        if ($lineItem->transferOrder->state->equals(Closed::class)) {
            return false;
        }

        return true;
    }

    public function delete(TenantUser $user, TransferOrderLineItem $lineItem): bool
    {
        if ($lineItem->transferOrder->state->equals(Closed::class)) {
            return false;
        }

        return true;
    }

    public function restore(TenantUser $user, TransferOrderLineItem $lineItem): bool
    {
        return true;
    }
}
