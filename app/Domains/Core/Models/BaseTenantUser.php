<?php

namespace App\Domains\Core\Models;

use App\Domains\Core\Models\Concerns\TenantConnection;

abstract class BaseTenantUser extends CoreUser
{
    use TenantConnection;
}
