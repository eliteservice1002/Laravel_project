<?php

namespace App\Domains\Finance\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\Concerns\TenantConnection;
use App\Domains\Core\Models\TenantModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $exchange_rate
 */
class ExchangeRate extends TenantModel
{
    use TenantConnection;
    use HasUlid;

    protected $casts = [
        'active_from' => 'datetime',
    ];

    public static function for(
        string $sourceCurrencyCode,
        string $targetCurrencyCode,
        Carbon $exchangeTime = null
    ): ?ExchangeRate {
        $exchangeTime ?? Carbon::now();

        return static::query()
            ->where('source_currency_code', $sourceCurrencyCode)
            ->where('target_currency_code', $targetCurrencyCode)
            ->where('active_from', '<=', $exchangeTime)
            ->orderBy('active_from', 'desc')
            ->first();
    }

    public function sourceCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'source_currency_code');
    }

    public function targetCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'target_currency_code');
    }
}
