<?php

namespace App\Domains\Core\Nova\Fields;

use Laravel\Nova\Fields\Field;

class ProgressBar extends Field
{
    public $component = 'progress-bar';

    public function options(array $options): static
    {
        return $this->withMeta(['options' => $options]);
    }
}
