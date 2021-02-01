<?php

namespace App\Domains\Core\Models\Casts;

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class MoneyCast implements CastsAttributes
{
    /**
     * {@inheritDoc}
     *
     * @throws UnknownCurrencyException
     */
    public function get($model, string $key, $value, array $attributes): Money
    {
        $amount = $attributes[$key.'_amount'] ?? 0;
        $currency = $attributes[$key.'_currency'] ?? 'SAR';

        return Money::ofMinor($amount, $currency);
    }

    /**
     * {@inheritDoc}
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        if ( ! $value instanceof Money) {
            throw new \InvalidArgumentException('The given value is not a Money object.');
        }

        return [
            $key.'_amount' => $value->getMinorAmount()->toInt(),
            $key.'_currency' => $value->getCurrency()->getCurrencyCode(),
        ];
    }
}
