<?php

namespace App\Domains\Core\Models\Scopes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EffectiveScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $this->applyEffectiveConstraints($builder, Carbon::now());
    }

    public function extend(Builder $builder): void
    {
        $this->addEffectiveOn($builder);
    }

    public function applyEffectiveConstraints(Builder $builder, Carbon $time): void
    {
        $builder
            ->where(function (Builder $builder) use ($time): void {
                $builder->where('effective_since', '<=', $time)
                    ->orWhere('effective_since', null);
            })
            ->where(function (Builder $builder) use ($time): void {
                $builder->where('effective_until', '>=', $time)
                    ->orWhere('effective_until', null);
            });
    }

    protected function addEffectiveOn(Builder $builder): void
    {
        $that = $this;

        $builder->macro('effectiveOn', function (Builder $builder, Carbon $time) use ($that) {
            $that->applyEffectiveConstraints($builder->withoutGlobalScope($this), $time);

            return $builder;
        });
    }
}
