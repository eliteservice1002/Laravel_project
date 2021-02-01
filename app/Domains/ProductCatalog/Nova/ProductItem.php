<?php

namespace App\Domains\ProductCatalog\Nova;

use App\Domains\Marketing\Nova\Slug;
use App\Domains\ProductCatalog\Nova\Actions\Publish;
use App\Domains\ProductCatalog\Nova\Actions\Unpublish;
use App\Domains\ProductCatalog\Nova\Fields\Published;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Dimensions;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;
use Spatie\NovaTranslatable\Translatable;
use Spatie\TagsField\Tags;

/**
 * @property \App\Domains\ProductCatalog\Models\ProductItem $resource
 */
class ProductItem extends Resource
{
    use HasSortableRows;

    public static $model = \App\Domains\ProductCatalog\Models\ProductItem::class;

    public static $displayInNavigation = false;

    public static $search = [
        'name',
        'code',
    ];

    public static $searchRelations = [
        'identifiers' => [
            'value',
        ],
    ];

    public function title(): string
    {
        return "[{$this->resource->code}] {$this->resource->product->name}";
    }

    public function fields(Request $request): array
    {
        $fields = [
            Text::make(__('Code'), 'code')
                ->readonly()
                ->hideWhenCreating()
                ->sortable(),
        ];

        /** @var ?\App\Domains\ProductCatalog\Models\Product $product */
        $product = null;

        if ($request->get('viaResource')) {
            /** @var \App\Domains\Core\Nova\Resource $resource */
            $resource = Nova::resourceForKey($request->get('viaResource'));
            /** @var \App\Domains\ProductCatalog\Models\Product $product */
            $product = $resource::newModel()->newQuery()->findOrFail($request->get('viaResourceId'));
        }

        $fields[] = BelongsTo::make('Product', 'product', Product::class)
            ->required();

        $fields[] = BelongsTo::make('Product Unit', 'unit', ProductUnit::class)
            ->default(fn () => $product ? $product->product_unit_id : null)
            ->required();

        $fields[] = Translatable::make([
            Text::make('Name')->required(),
        ]);

        if ( ! is_null($product)) {
            $product->attributes
                ->each(function (\App\Domains\ProductCatalog\Models\ProductAttribute $attribute) use (&$fields) {
                    $options = $attribute->options
                        ->map(fn (\App\Domains\ProductCatalog\Models\ProductAttributeOption $option) => $option->name)
                        ->filter();

                    $fields[] = Select::make($attribute->name, "productAttribute_{$attribute->id}")
                        // ->required()
                        ->resolveUsing(function ($_, $model, $attribute) {
                            $id = explode('_', $attribute, 2)[1];
                            /** @var \App\Domains\ProductCatalog\Models\ProductItem $model */
                            return optional($model->attributeOptions->first(fn ($o) => $o->product_attribute_id == $id))
                                ->id;
                        })
                        ->displayUsing(function ($_, $model, $attribute) {
                            $id = explode('_', $attribute, 2)[1];
                            /** @var \App\Domains\ProductCatalog\Models\ProductItem $model */
                            return optional($model->attributeOptions->first(fn ($o) => $o->product_attribute_id == $id))
                                ->name;
                        })
                        ->options($options);
                });
        }

        $fields[] = Tags::make('Tags')
            ->type('product')
            ->hideFromIndex();

        $fields[] = Published::make();

        $fields[] = Images::make('Gallery', 'default')
            ->conversionOnPreview('responsive')
            ->conversionOnDetailView('responsive')
            ->conversionOnForm('responsive')
            ->conversionOnIndexView('responsive')
            ->fullSize()
            ->withResponsiveImages()
            ->rules('max:10')
            ->showStatistics()
            ->singleImageRules((new Dimensions())->ratio(2 / 3));

        $fields[] = HasMany::make('Identifiers', 'identifiers', ProductItemIdentifier::class);

        $fields[] = HasMany::make('Sale Prices', 'salePrices', ProductItemSalePrice::class);

        $fields[] = new Tabs('Related', [
            MorphMany::make('Product Collection Items', 'productCollectionItems', ProductCollectionItem::class),

            MorphMany::make('Slugs', 'slugs', Slug::class),
        ]);

        // $fields[] = Commenter::make('Internal Comments'),

        return $fields;
    }

    public function actions(Request $request)
    {
        return array_merge([
            new Publish(),
            new Unpublish(),
        ], parent::actions($request));
    }

    protected static function fillFields(NovaRequest $request, $model, $fields): array
    {
        /** @var \App\Domains\ProductCatalog\Models\ProductItem $model */
        /** @var \App\Domains\ProductCatalog\Models\Product $product */
        $product = $model->product;
        if ( ! $model->exists && $request->get('viaResource')) {
            /** @var \App\Domains\Core\Nova\Resource $resource */
            $resource = Nova::resourceForKey($request->get('viaResource'));
            $product = $resource::newModel()->newQuery()->findOrFail($request->get('viaResourceId'));
        }

        $productAttributes = array_map(fn ($v) => "productAttribute_{$v}", $product->attributes->modelKeys());
        debug($product->attributes);

        $productAttributeOptions = $request->only($productAttributes);
        array_walk($productAttributes, fn ($k) => $request->request->remove($k));

        [$model, $callbacks] = parent::fillFields($request, $model, $fields);

        $callbacks[] = function () use ($model, $productAttributeOptions) {
            $model->attributeOptions()->sync(array_filter($productAttributeOptions));
        };

        return [$model, $callbacks];
    }
}
