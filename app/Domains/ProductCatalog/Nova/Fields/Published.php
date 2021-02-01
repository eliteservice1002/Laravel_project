<?php

namespace App\Domains\ProductCatalog\Nova\Fields;

use Laravel\Nova\Fields\Badge;

class Published extends Badge
{
    public function __construct($name = 'Published', $attribute = 'published_at', callable $resolveCallback = null)
    {
        if (is_null($resolveCallback)) {
            $resolveCallback = fn ($value) => is_null($value) ? 'hidden' : 'published';
        }

        parent::__construct($name, $attribute, $resolveCallback);

        $this->map(['hidden' => 'warning', 'published' => 'success']);
    }
}
