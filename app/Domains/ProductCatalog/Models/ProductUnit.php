<?php

namespace App\Domains\ProductCatalog\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\Marketing\Models\Slug;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $symbol
 */
class ProductUnit extends TenantModel
{
    use HasUlid;
    use HasTranslations;

    public $translatable = ['name', 'symbol'];

    public function slugs(): MorphMany
    {
        return $this->morphMany(Slug::class, 'linked');
    }
}
