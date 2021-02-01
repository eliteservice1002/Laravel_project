<?php

namespace App\Domains\OrderFulfillment\Models;

use App\Domains\Core\Models\Concerns\HasTags;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\Tenants\Models\Store;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRequest extends TenantModel
{
    use HasUlid;
    use HasTags;

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
