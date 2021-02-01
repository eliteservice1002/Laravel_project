<?php

namespace App\Domains\Purchasing\Models;

use App\Domains\Core\Models\Concerns\HasSequentialCode;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\Inventory\Models\InventoryArea;
use App\Domains\Purchasing\States\PurchaseOrderState\PurchaseOrderState;
use App\Domains\Vendors\Models\Vendor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\ModelStates\HasStates;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $code
 * @property Carbon $issue_date
 * @property Carbon $delivery_date
 * @property PurchaseOrderState|string $state
 * @property int $vendor_id
 * @property int $delivery_area_id
 * @property Vendor $vendor
 * @property InventoryArea $deliveryArea
 * @property Collection|PurchaseInvoice[] $invoices
 * @property Collection|PurchaseOrderLineItem[] $lineItems
 */
class PurchaseOrder extends TenantModel
{
    use HasUlid;
    use HasSequentialCode;
    use HasTranslations;
    use HasStates;

    protected bool $sequentialCodeOnCreation = false;
    protected int $sequentialCodePaddingLength = 6;

    protected $casts = [
        'issue_date' => 'date:Y-m-d',
        'delivery_date' => 'date:Y-m-d',
        'state' => PurchaseOrderState::class,
    ];

    protected $with = ['lineItems'];

    protected array $translatable = ['name'];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function deliveryArea(): BelongsTo
    {
        return $this->belongsTo(InventoryArea::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(PurchaseInvoice::class, 'purchase_invoice_id');
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderLineItem::class);
    }
}
