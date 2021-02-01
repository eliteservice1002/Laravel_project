<?php

namespace App\Domains\Marketing\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentPage extends TenantModel
{
    use HasUlid;

    public function store(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Tenants\Models\Store::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(ContentPageBlock::class, 'content_page_id');
    }
}
