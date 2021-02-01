<?php

namespace App\Domains\Finance\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $name
 * @property Collection|TaxRate[] $rates
 */
class Tax extends TenantModel
{
    use HasUlid;
    use HasTranslations;

    protected array $translatable = ['name'];

    public function rates(): HasMany
    {
        return $this->hasMany(TaxRate::class);
    }
}
