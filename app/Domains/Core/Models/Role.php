<?php

namespace App\Domains\Core\Models;

use App\Domains\Core\Models\Concerns\HasUlid;

class Role extends TenantModel
{
    use HasUlid;
}
