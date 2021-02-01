<?php

namespace App\Domains\ProductCatalog\Nova\Actions;

use App\Domains\ProductCatalog\Models\Concerns\Publishable;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class Publish extends Action
{
    public function handle(ActionFields $fields, Collection $models): array
    {
        foreach ($models as $model) {
            if (in_array(Publishable::class, class_uses_recursive($model))) {
                if ($model->isPublished()) {
                    $model->publish();
                }
            }
        }

        return Action::message('It worked!');
    }

    public function fields(): array
    {
        return [];
    }
}
