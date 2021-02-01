<?php

namespace App\Domains\ProductCatalog\Observers;

use App\Domains\ProductCatalog\Models\ProductType;
use Illuminate\Support\Str;

class ProductTypeObserver
{
    public function created(ProductType $productType): void
    {
        // TODO(ibrasho): Handle duplicated slugs.
        foreach ($productType->getTranslatedLocales('name') as $locale => $name) {
            $language = $locale == 'en' ? $locale : null;

            $productType->slugs()->updateOrCreate([
                'path' => 'pt/'.$productType->code.'-'.Str::slug($name, '-', $language),
                'locale' => $locale,
            ]);
        }
    }
}
