<?php

namespace App\Domains\Core\Models\Concerns;

use App\Domains\Core\Models\Casts\PeriodCast;
use App\Domains\Core\Models\Scopes\EffectiveScope;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;

/**
 * @property Carbon $effective_since
 * @property Carbon $effective_until
 * @property CarbonPeriod effective_period
 */
trait HasEffectivePeriod
{
    public static function bootHasEffectivePeriod()
    {
        static::addGlobalScope(new EffectiveScope());
    }

    public function initializeHasEffectivePeriod()
    {
        if ( ! isset($this->casts['effective_since'])) {
            $this->casts['effective_since'] = 'datetime';
        }

        if ( ! isset($this->casts['effective_until'])) {
            $this->casts['effective_until'] = 'datetime';
        }

        if ( ! isset($this->casts['effective_period'])) {
            $this->casts['effective_period'] = PeriodCast::class;
        }
    }
}
