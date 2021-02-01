<?php

namespace App\Domains\Core\Models;

use App\Domains\Core\Models\Concerns\TenantConnection;

abstract class TenantPivot extends CorePivot
{
    use TenantConnection;
}
