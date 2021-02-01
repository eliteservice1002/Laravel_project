<?php

namespace App\Domains\Inventory\Policies;

use App\Domains\Core\Models\CoreUser;
use App\Domains\Inventory\Models\TransferOrder;
use App\Domains\Inventory\States\TransferOrderState\Closed;
use App\Domains\Inventory\States\TransferOrderState\Draft;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransferOrderPolicy
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

    public function view(TenantUser $user, TransferOrder $order): bool
    {
        return true;
    }

    public function create(TenantUser $user): bool
    {
        return true;
    }

    public function update(TenantUser $user, TransferOrder $order): bool
    {
        if ($order->state->equals(Closed::class)) {
            return false;
        }

        return true;
    }

    public function delete(TenantUser $user, TransferOrder $order): bool
    {
        return true;
    }

    public function restore(TenantUser $user, TransferOrder $order): bool
    {
        return true;
    }

    public function addTransferOrderItem(TenantUser $user, TransferOrder $order): bool
    {
        if ( ! $order->state->equals(Draft::class)) {
            return false;
        }

        return true;
    }
}
