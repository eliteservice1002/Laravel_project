<?php

namespace App\Domains\Tenants\Models;

use App\Domains\Core\Models\BaseTenantUser;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\Concerns\TenantConnection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $ulid
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Tenant $tenant
 */
class TenantUser extends BaseTenantUser
{
    use TenantConnection;
    use HasUlid;

    protected $fillable = [
        'name',
        'email',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
