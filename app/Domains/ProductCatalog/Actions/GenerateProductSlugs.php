<?php

namespace App\Domains\ProductCatalog\Actions;

use App\Domains\Core\Actions\DomainAction;
use App\Domains\ProductCatalog\Models\Product;
use Illuminate\Support\Str;

class GenerateProductSlugs extends DomainAction
{
    public function handle(Product $product): void
    {
        // TODO(ibrasho): Handle duplicated slugs.
        foreach ($product->getTranslatedLocales('name') as $locale => $name) {
            $language = $locale == 'en' ? $locale : null;

            $product->slugs()->updateOrCreate([
                'path' => 'p/'.$product->code.'-'.Str::slug($name, '-', $language),
                'locale' => $locale,
            ]);
        }
    }
}
