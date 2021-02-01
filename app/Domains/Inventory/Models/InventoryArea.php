<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Core\Models\Concerns\HasSequentialCode;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\Inventory\Models\Enums\InventoryAreaType;
use App\Domains\ProductCatalog\Models\ProductItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $inventory_location_id
 * @property InventoryAreaType $type
 * @property InventoryLocation $inventoryLocation
 * @property Collection|ProductItem[] $productItems
 * @property Collection|InventoryItem[] $inventoryItems
 * @property Collection|InventoryMovement[] $inventoryMovements
 */
class InventoryArea extends TenantModel
{
    use HasUlid;
    use HasTranslations;
    use HasSequentialCode;

    protected $fillable = [
        'code',
        'name',
        'type',
    ];

    protected $casts = [
        'type' => InventoryAreaType::class,
    ];

    // protected $with = ['inventoryLocation'];

    protected array $translatable = ['name'];

    protected int $sequentialCodePaddingLength = 3;

    public function inventoryLocation(): BelongsTo
    {
        return $this->belongsTo(InventoryLocation::class, 'inventory_location_id');
    }

    public function productItems(): BelongsToMany
    {
        return $this->belongsToMany(ProductItem::class, InventoryItem::getTableName())
            ->withPivot(['stock'])
            ->using(InventoryItem::class);
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function originatingTransferOrders(): HasMany
    {
        return $this->hasMany(TransferOrder::class, 'source_area_id');
    }

    public function terminatingTransferOrders(): HasMany
    {
        return $this->hasMany(TransferOrder::class, 'target_area_id');
    }

    public static function getSequentialCodePrefix(self $instance): string
    {
        $locationPrefix = Str::after($instance->inventoryLocation->code,
            InventoryLocation::getSequentialCodePrefix($instance->inventoryLocation));

        return static::$prefixMap[static::class].$locationPrefix;
    }

    protected function getSequentialCodeHighest(): int
    {
        $highest = $this->getSequentialCodeQuery()
            ->max($this->getSequentialCodeColumn());

        if (is_null($highest)) {
            return $this->getSequentialCodeFallback();
        }

        return (int) Str::after($highest, static::getSequentialCodePrefix($this)) + 1;
    }
}
