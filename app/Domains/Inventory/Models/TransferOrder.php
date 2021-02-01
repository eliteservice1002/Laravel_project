<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Core\Models\Concerns\HasSequentialCode;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\Inventory\States\TransferOrderState\TransferOrderState;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\ModelStates\HasStates;

/**
 * @property int $id
 * @property string $code
 * @property int $source_area_id
 * @property int $target_area_id
 * @property string|TransferOrderState $state
 * @property Carbon $issue_date
 * @property InventoryArea $sourceArea
 * @property InventoryArea $targetArea
 * @property Collection|TransferOrderLineItem[] $lineItems
 */
class TransferOrder extends TenantModel
{
    use HasUlid;
    use HasSequentialCode;
    use HasStates;

    protected int $sequentialCodePaddingLength = 6;

    protected $fillable = [
        'source_area_id',
        'target_area_id',
        'issue_date',
    ];

    protected $casts = [
        'issue_date' => 'datetime:Y-m-d',
        'state' => TransferOrderState::class,
    ];

    public function sourceArea(): BelongsTo
    {
        return $this->belongsTo(InventoryArea::class);
    }

    public function targetArea(): BelongsTo
    {
        return $this->belongsTo(InventoryArea::class);
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(TransferOrderLineItem::class);
    }
}
