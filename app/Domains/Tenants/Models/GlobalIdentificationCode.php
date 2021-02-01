<?php

namespace App\Domains\Tenants\Models;

use App\Domains\Core\Models\Concerns\HasUlidKey;
use App\Domains\Core\Models\TenantModel;

/**
 * @property string $code
 */
class GlobalIdentificationCode extends TenantModel
{
    use HasUlidKey;

    public $timestamps = false;
}
