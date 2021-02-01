<?php

namespace App\Domains\Core\Models;

use App\Domains\Core\Models\Concerns\TenantConnection;
use App\Domains\Tenants\Models\InternalComment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class TenantModel extends CoreModel
{
    use TenantConnection;

    public function internalComments(): MorphMany
    {
        return $this->morphMany(InternalComment::class, 'commentable');
    }
}
