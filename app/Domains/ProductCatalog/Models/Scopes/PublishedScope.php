<?php

namespace App\Domains\ProductCatalog\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PublishedScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
    }

    public function extend(Builder $builder): void
    {
        $this->addOnlyPublished($builder);
        $this->addOnlyUnpublished($builder);
    }

    protected function addOnlyPublished(Builder $builder): void
    {
        $builder->macro('onlyPublished', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedPublishedColumn(), true);
        });
    }

    protected function addOnlyUnpublished(Builder $builder): void
    {
        $builder->macro('onlyUnpublished', function (Builder $builder) {
            return $builder->where($builder->getModel()->getQualifiedPublishedColumn(), false);
        });
    }
}
