<?php

namespace App\Domains\Core\Models\Concerns;

use App\Domains\Tenants\Models\Tag;

trait HasTags
{
    use \Spatie\Tags\HasTags;

    public static function getTagClassName(): string
    {
        return Tag::class;
    }
}
