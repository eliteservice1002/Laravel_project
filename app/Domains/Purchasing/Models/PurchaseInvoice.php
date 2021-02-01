<?php

namespace App\Domains\Purchasing\Models;

use App\Domains\Core\Models\Concerns\HasSequentialCode;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\Vendors\Models\Vendor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $code
 * @property Carbon $issue_date
 * @property Vendor $vendor
 * @property PurchaseOrder $purchaseOrder
 * @property Collection|PurchaseInvoiceLineItem[] $lineItems
 */
class PurchaseInvoice extends TenantModel
{
    use HasUlid;
    use HasSequentialCode;
    use HasTranslations;

    protected int $sequentialCodePaddingLength = 6;

    protected array $translatable = ['name'];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function purchaseOrder(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(PurchaseInvoiceLineItem::class);
    }
}
