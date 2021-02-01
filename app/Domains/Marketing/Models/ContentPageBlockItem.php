<?php

namespace App\Domains\Marketing\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContentPageBlockItem extends TenantModel
{
    use HasUlid;

    public function block(): BelongsTo
    {
        return $this->belongsTo(ContentPageBlock::class, 'content_page_block_id');
    }

    public function link(): MorphTo
    {
        return $this->morphTo();
    }
}
