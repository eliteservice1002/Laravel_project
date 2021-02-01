<?php

namespace App\Domains\Tenants\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\CoreModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $name
 * @property Tenant $tenant
 * @property string $domain
 */
class Store extends CoreModel
{
    use HasUlid;
    use HasTranslations;

    protected array $translatable = ['name'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
