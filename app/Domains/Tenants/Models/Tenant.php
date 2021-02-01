<?php

namespace App\Domains\Tenants\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\CoreModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $name
 * @property string $domain
 * @property Collection|Store[] $stores
 */
class Tenant extends CoreModel
{
    use HasUlid;
    use HasTranslations;

    protected array $translatable = ['name'];

    public function users(): HasMany
    {
        return $this->hasMany(TenantUser::class);
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function getSchema(): string
    {
        return 'tenant_'.strtolower($this->getKey());
    }
}
