<?php

namespace App\Domains\Core\Services\LaravelMediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class UuidPathGenerator extends DefaultPathGenerator implements PathGenerator
{
    protected function getBasePath(Media $media): string
    {
        return $media->uuid;
    }
}
