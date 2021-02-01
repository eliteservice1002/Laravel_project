<?php

namespace App\Domains\Customers\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use Laravel\Passport\HasApiTokens;

class Customer extends TenantModel
{
    use HasUlid;
    use HasApiTokens;
}
