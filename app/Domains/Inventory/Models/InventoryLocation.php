<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Core\Models\Concerns\HasSequentialCode;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property Collection|InventoryArea $areas
 */
class InventoryLocation extends TenantModel
{
    use HasUlid;
    use HasTranslations;
    use HasSequentialCode;

    protected $fillable = [
        'code',
        'name',
    ];

    protected array $translatable = ['name'];

    protected int $sequentialCodePaddingLength = 2;

    public function areas(): HasMany
    {
        return $this->hasMany(InventoryArea::class, 'inventory_location_id');
    }
}
