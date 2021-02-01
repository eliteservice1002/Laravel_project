<?php

namespace App\Domains\Core\Models;

use App\Domains\Core\Models\Concerns\TenantConnection;

class Audit extends \OwenIt\Auditing\Models\Audit implements \OwenIt\Auditing\Contracts\Audit
{
    use \OwenIt\Auditing\Audit;
    use TenantConnection {
        TenantConnection::getConnectionName insteadof \OwenIt\Auditing\Audit;
    }

    protected $guarded = [];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];
}
