<?php

namespace App\Domains\Core\Models\Concerns;

use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

/**
 * @method static void saving(callable $callable)
 */
trait HasSlug
{
    public static function bootHasSlug()
    {
        static::saving(function (HasTranslations $model) {
            collect($model->getTranslatedLocales('name'))
                ->each(
                    function (string $locale) use ($model) {
                        if ( ! $model->hasTranslation('slug', $locale)) {
                            $model->setTranslation('slug', $locale, Str::slug($this->getTranslation('name', $locale)));
                        }
                    }
                );
        });
    }
}
