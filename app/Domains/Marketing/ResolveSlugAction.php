<?php

namespace App\Domains\Marketing;

use App\Domains\Core\Actions\DomainAction;
use App\Domains\Marketing\Models\Slug;
use App\Domains\ProductCatalog\Models\Product;
use App\Domains\ProductCatalog\Models\ProductCollection;
use App\Domains\ProductCatalog\Models\ProductItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ResolveSlugAction extends DomainAction
{
    public function handle($path, $locale): Response
    {
        $slug = Slug::query()
            ->where('path', $path)
            ->firstOrFail();

        if ($slug->locale !== $locale) {
        }

        switch ($slug->linked::class) {
            case ProductItem::class:
                1;

                break;

            case ProductCollection::class:
                2;

                break;

            case Product::class:
                3;

                break;
        }

        // dd($path, $locale, $slug->linked);

        return new Response('');
    }

    public function asController(Request $request, $locale, $path): Response
    {
        $article = $this->handle($locale, $path);

        return redirect()->route('articles.show', [$article]);
    }
}
