<?php

namespace App\Domains\Purchasing\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;

class PurchasePaymentTerm extends TenantModel
{
    use HasUlid;
}
