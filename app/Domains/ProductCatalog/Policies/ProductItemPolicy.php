<?php

namespace App\Domains\ProductCatalog\Policies;

use App\Domains\Core\Models\CoreUser;
use App\Domains\ProductCatalog\Models\ProductItem;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductItemPolicy
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

    public function view(TenantUser $user, ProductItem $productItem): bool
    {
        return true;
    }

    public function create(TenantUser $user): bool
    {
        return true;
    }

    public function update(TenantUser $user, ProductItem $productItem): bool
    {
        return true;
    }

    public function delete(TenantUser $user, ProductItem $productItem): bool
    {
        if ($productItem->main) {
            return false;
        }

        return true;
    }

    public function restore(TenantUser $user, ProductItem $productItem): bool
    {
        return true;
    }
}
