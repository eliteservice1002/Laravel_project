<?php

namespace App\Domains\Core\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends TenantModel
{
    use HasUlid;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, RolePermission::getTableName())
            ->using(RolePermission::class);
    }
}
