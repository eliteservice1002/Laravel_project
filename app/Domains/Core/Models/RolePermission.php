<?php

namespace App\Domains\Core\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends TenantPivot
{
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }
}
