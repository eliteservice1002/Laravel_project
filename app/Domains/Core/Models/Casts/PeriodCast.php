<?php

namespace App\Domains\Core\Models\Casts;

use App\Domains\Core\Exceptions\PlatformRuntimeException;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PeriodCast implements CastsAttributes
{
    /**
     * {@inheritDoc}
     */
    public function get($model, string $key, $value, array $attributes): CarbonPeriod
    {
        /** @var CarbonPeriod $value */
        $key = Str::before($key, '_period');

        $since = $this->{$key.'_since'};
        $until = $this->{$key.'_until'};

        if (is_null($since)) {
            throw new PlatformRuntimeException('Period cannot have NULL as start date.');
        }

        return CarbonPeriod::between($since, $until);
    }

    /**
     * {@inheritDoc}
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        if ( ! $value instanceof CarbonPeriod) {
            throw new InvalidArgumentException('The given value is not a '.CarbonPeriod::class.' object.');
        }

        /** @var CarbonPeriod $value */
        $key = Str::before($key, '_period');

        return [
            $key.'_since' => $value->start,
            $key.'_until' => $value->end,
        ];
    }
}
