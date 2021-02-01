<?php

namespace App\Domains\Checkout\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;

class Cart extends TenantModel
{
    use HasUlid;
}
