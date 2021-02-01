<?php

namespace App\Domains\Tenants\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\Concerns\TenantConnection;
use App\Domains\Marketing\Models\Slug;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Tag extends \Spatie\Tags\Tag
{
    use TenantConnection;
    use HasUlid;

    public function slugs(): MorphMany
    {
        return $this->morphMany(Slug::class, 'linked');
    }
}
