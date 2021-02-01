<?php

namespace App\Domains\OrderFulfillment\Models;

use App\Domains\Core\Models\Concerns\HasTags;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends TenantModel
{
    use HasUlid;
    use HasTags;

    public function store(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Tenants\Models\Store::class);
    }
}
