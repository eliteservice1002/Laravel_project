<?php

namespace App\Domains\Core\Nova\Fields;

use Brick\Math\RoundingMode;
use Laravel\Nova\Fields\Currency;

class Money extends Currency
{
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->fillUsing(function ($request, $model, $attribute) {
            $value = $request->{$attribute};

            $model->{$attribute} = \Brick\Money\Money::of($value, $this->currency, null, RoundingMode::HALF_EVEN);
        })
            ->displayUsing(function (\Brick\Money\Money $value) {
                if ($this->isNullValue($value)) {
                    return null;
                }

                return $value->formatTo($this->locale);
            })
            ->resolveUsing(function ($value, $resource) {
                if ($this->isNullValue($value)) {
                    return null;
                }

                return (string) $resource->{$this->attribute}->getAmount();
            });
    }
}
