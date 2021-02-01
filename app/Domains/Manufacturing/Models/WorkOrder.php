<?php

namespace App\Domains\Manufacturing\Models;

use App\Domains\Core\Models\Concerns\HasSequentialCode;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\Inventory\Models\InventoryArea;
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
 * @property InventoryArea $deliveryArea
 * @property Collection|WorkOrderLineItem[] $workOrderItems
 */
class WorkOrder extends TenantModel
{
    use HasUlid;
    use HasSequentialCode;
    use HasTranslations;

    protected array $translatable = ['name'];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function deliveryArea(): BelongsTo
    {
        return $this->belongsTo(InventoryArea::class);
    }

    public function workOrderItems(): HasMany
    {
        return $this->hasMany(WorkOrderLineItem::class);
    }
}
