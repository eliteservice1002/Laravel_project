<?php

namespace App\Domains\Marketing\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionRule extends TenantModel
{
    use HasUlid;

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }
}
