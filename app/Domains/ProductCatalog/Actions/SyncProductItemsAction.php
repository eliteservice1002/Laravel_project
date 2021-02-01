<?php

namespace App\Domains\ProductCatalog\Actions;

use App\Domains\Core\Actions\QueuedAction;
use App\Domains\ProductCatalog\Models\Product;
use App\Domains\ProductCatalog\Models\ProductAttribute;
use App\Domains\ProductCatalog\Models\ProductItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class SyncProductItemsAction
{
    use QueuedAction;

    public function execute(Product $product): void
    {
        $product->getConnection()->transaction(function () use ($product): void {
            $product->productItems()->with('attributeOptions.attribute')->get();

            $attributes = $product->type->attributes()->with('attributeOptions')
                ->get()
                ->filter(fn (ProductAttribute $attribute) => $attribute->attributeOptions->isNotEmpty());

            // ProductItem::query()->getConnection()->unprepared('LOCK TABLE '.ProductItem::getTableName().' IN ACCESS EXCLUSIVE MODE;');

            /** @var \Illuminate\Database\Eloquent\Collection $combinations */
            // Get the values of the first option.
            $combinations = $attributes->shift()->values
                // Cross join all option values to generate all combinations
                ->crossJoin(...$attributes->pluck('attributeOptions')->all());

            $combinations->whenEmpty(fn (Collection $c) => $c->add([]));

            /** @var Collection|ProductItem[] $productItems */
            $productItems = $product->productItems()
                ->with('attributeOptions')
                ->get()
                ->keyBy(function (ProductItem $productItem) {
                    return collect($productItem->attributeOptions->modelKeys())->sort()->implode('-');
                });

            $hasMain = $productItems->contains('main', true);

            $combinations->each(function ($attributeOptions) use ($product, &$hasMain, $productItems) {
                $key = collect($attributeOptions)->sort()->implode('-');

                /** @var ProductItem $productItem */
                if ($productItems->has($key)) {
                    $productItem = $productItems->get($key);
                } else {
                    $productItem = $product->productItems()->make();
                }

                $productItem->type()->associate($product->type);
                $productItem->unit()->associate($product->unit);

                $productItem->setTranslations('name', [
                    'ar' => trim(collect($attributeOptions)->reduce(fn ($c, $o) => $c.' '.$o->name, '')),
                    'en' => trim(collect($attributeOptions)->reduce(fn ($c, $o) => $c.' '.$o->name, '')),
                ]);

                if ( ! $hasMain) {
                    $productItem->main = ! $hasMain;
                    $hasMain = true;
                }

                $productItem->save();

                $productItem->attributeOptions()->attach(Arr::pluck($attributeOptions, 'id'));
            });
        });
    }
}
