<?php

namespace App\Domains\Accounting\Models;

use App\Domains\Accounting\Models\Enums\AccountingAccountType;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $name
 * @property string $code
 * @property AccountingAccountType $type
 */
class AccountingAccount extends TenantModel
{
    use HasUlid;
    use HasTranslations;

    protected $translatable = ['name'];

    protected $casts = [
        'type' => AccountingAccountType::class,
    ];
}
