<?php

namespace App\Domains\Finance\Services;

use App\Domains\Finance\Models\ExchangeRate;
use Brick\Math\BigNumber;
use Brick\Money\Exception\CurrencyConversionException;
use Carbon\Carbon;

class EloquentExchangeRateProvider implements \Brick\Money\ExchangeRateProvider
{
    private Carbon $exchangeDate;

    public function __construct(Carbon $exchangeDate)
    {
        $this->exchangeDate = $exchangeDate;
    }

    public function getExchangeRate(string $sourceCurrencyCode, string $targetCurrencyCode): BigNumber
    {
        $rate = ExchangeRate::for(
            $sourceCurrencyCode,
            $targetCurrencyCode,
            $this->exchangeDate,
        );

        if (is_null($rate)) {
            throw CurrencyConversionException::exchangeRateNotAvailable($sourceCurrencyCode, $targetCurrencyCode);
        }

        return BigNumber::of($rate->exchange_rate);
    }
}
