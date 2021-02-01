<?php

namespace App\Domains\Finance\Models;

use App\Domains\Core\Models\TenantModel;
use Spatie\Translatable\HasTranslations;

class Currency extends TenantModel
{
    use HasTranslations;

    public $incrementing = false;

    protected $primaryKey = 'code';

    protected $keyType = 'string';

    protected array $translatable = ['name', 'symbol'];
}
