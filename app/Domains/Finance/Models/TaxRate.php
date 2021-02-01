<?php

namespace App\Domains\Finance\Models;

use App\Domains\Core\Models\Concerns\HasEffectivePeriod;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property float $percentage
 * @property Tax $tax
 */
class TaxRate extends TenantModel
{
    use HasUlid;
    use HasEffectivePeriod;
    use HasTranslations;

    protected array $translatable = ['name'];

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }
}
