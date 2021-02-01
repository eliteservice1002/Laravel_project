<?php

namespace App\Domains\Tenants\Models;

use App\Domains\Core\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InternalComment extends TenantModel
{
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function author(): MorphTo
    {
        return $this->morphTo();
    }
}
