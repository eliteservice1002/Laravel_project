<?php

namespace App\Domains\Marketing\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ContentPageBlock extends TenantModel
{
    use HasTranslations;
    use HasUlid;

    protected $translatable = ['label', 'show_more_label'];

    public function page(): BelongsTo
    {
        return $this->belongsTo(ContentPage::class, 'content_page_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ContentPageBlockItem::class, 'content_page_block_id');
    }
}
