<?php

namespace App\Domains\Tenants\Events;

use App\Domains\Tenants\Models\Store;

class StoreSet
{
    public function __construct(
        public Store $store,
    ) {
    }
}
