<?php

namespace App\Domains\ProductCatalog\Policies;

use App\Domains\Core\Models\CoreUser;
use App\Domains\ProductCatalog\Models\ProductItemSalePrice;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductItemPricePolicy
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

    public function view(TenantUser $user, ProductItemSalePrice $price): bool
    {
        return true;
    }

    public function create(TenantUser $user): bool
    {
        return true;
    }

    public function update(TenantUser $user, ProductItemSalePrice $price): bool
    {
        return false;
    }

    public function delete(TenantUser $user, ProductItemSalePrice $price): bool
    {
        return false;
    }

    public function restore(TenantUser $user, ProductItemSalePrice $price): bool
    {
        return false;
    }
}
