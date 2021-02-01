<?php

namespace App\Domains\ProductCatalog\Policies;

use App\Domains\Core\Models\CoreUser;
use App\Domains\ProductCatalog\Models\ProductItemIdentifier;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductItemIdentifierPolicy
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

    public function view(TenantUser $user, ProductItemIdentifier $identifier): bool
    {
        return true;
    }

    public function create(TenantUser $user): bool
    {
        return true;
    }

    public function update(TenantUser $user, ProductItemIdentifier $identifier): bool
    {
        return false;
    }

    public function delete(TenantUser $user, ProductItemIdentifier $identifier): bool
    {
        return false;
    }

    public function restore(TenantUser $user, ProductItemIdentifier $identifier): bool
    {
        return false;
    }
}
