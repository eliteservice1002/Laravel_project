<?php

namespace App\Domains\Finance\Actions;

use App\Domains\Core\Actions\DomainAction;
use App\Domains\Finance\Models\ExchangeRate;

class UpdateExchangeRate extends DomainAction
{
    public function handle(): void
    {
        ExchangeRate::query()->create();
    }
}
