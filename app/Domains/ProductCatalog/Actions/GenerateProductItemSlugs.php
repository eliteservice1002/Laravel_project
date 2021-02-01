<?php

namespace App\Domains\ProductCatalog\Actions;

use App\Domains\Core\Actions\DomainAction;
use App\Domains\ProductCatalog\Models\ProductItem;
use Illuminate\Support\Str;

class GenerateProductItemSlugs extends DomainAction
{
    public function handle(ProductItem $productItem): void
    {
        // TODO(ibrasho): Handle duplicated slugs.
        foreach ($productItem->getTranslatedLocales('name') as $locale => $name) {
            $language = $locale == 'en' ? $locale : null;

            $productItem->slugs()->updateOrCreate([
                'path' => 'p/'.$productItem->code.'-'.Str::slug($name, '-', $language),
                'locale' => $locale,
            ]);
        }
    }
}
