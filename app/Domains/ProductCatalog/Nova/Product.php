<?php

namespace App\Domains\ProductCatalog\Nova;

use App\Domains\Marketing\Nova\Slug;
use App\Domains\ProductCatalog\Imports\ProductItemsImport;
use App\Domains\ProductCatalog\Nova\Actions\Publish;
use App\Domains\ProductCatalog\Nova\Actions\Unpublish;
use App\Domains\Tenants\Nova\Tag;
use Devpartners\AuditableLog\AuditableLog;
use Eminiarts\Tabs\Tabs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Filters\Filter;
use Maatwebsite\Excel\Facades\Excel;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;
use Spatie\NovaTranslatable\Translatable;
use Spatie\TagsField\Tags;

/**
 * @property \App\Domains\ProductCatalog\Models\Product $resource
 */
class Product extends Resource
{
    use HasSortableRows;

    public static $model = \App\Domains\ProductCatalog\Models\Product::class;

    public static $search = [
        'code',
        'name',
    ];

    public static $with = ['productItems'];

    public function title(): string
    {
        return "[{$this->resource->code}] {$this->resource->name}";
    }

    public function fields(Request $request): array
    {
        return [
            Text::make(__('Code'), 'code')
                ->required()
                ->sortable(),

            // NestedForm::make('Product Items', 'productItems', ProductItem::class),

            BelongsTo::make('Product Type', 'type', ProductType::class)
                ->required(),

            BelongsTo::make('Product Unit', 'unit', ProductUnit::class)
                ->hideFromIndex()
                ->required(),

            Number::make('Items', fn (\App\Domains\ProductCatalog\Models\Product $resource) => $resource->productItems()->count()),

            Avatar::make('Image')
                ->resolveUsing(fn ($resource, $attribute) => optional($this->resource->mainProduct)->getFirstMediaUrl())
                ->thumbnail(fn ($value) => $value)
                ->preview(fn ($value) => $value)
                ->hideFromDetail()
                ->exceptOnForms(),

            Translatable::make([
                Text::make('Name')
                    ->required(),

                Trix::make('Description')
                    ->required(),
            ]),

            Tags::make('Tags')
                ->type('product')
                ->withLinkToTagResource(Tag::class)
                ->hideFromIndex(),

            new Tabs('Related', [
                HasMany::make('Product Items', 'productItems', ProductItem::class),

                BelongsToMany::make('Attributes', 'attributes', ProductAttribute::class),

                MorphMany::make('Collection Items', 'productCollectionItems', ProductCollectionItem::class),

                MorphMany::make('Slugs', 'slugs', Slug::class),
            ]),

            // Commenter::make('Internal Comments'),

            AuditableLog::make(),
        ];
    }

    public function actions(Request $request)
    {
        return array_merge([
            new Publish(),
            new Unpublish(),
            new class() extends Action {
                public $name = 'Import';

                public $standalone = true;

                public function fields(): array
                {
                    return [
                        File::make('File')
                            ->rules('required'),
                    ];
                }

                public function handle(ActionFields $fields): array
                {
                    Excel::import(new ProductItemsImport(), $fields->get('file'));

                    return Action::message('It worked!');
                }
            },
        ], parent::actions($request));
    }

    public function filters(Request $request)
    {
        return array_merge([
            new class() extends Filter {
                public $name = 'Product Type';

                public function apply(Request $request, $query, $value): Builder
                {
                    return $query->where('product_type_id', $value);
                }

                public function options(Request $request): array
                {
                    return \App\Domains\ProductCatalog\Models\ProductType::query()
                        ->get()
                        ->keyBy(fn ($productType) => "[{$productType->code}] {$productType->name}")
                        ->map(fn ($productType) => $productType->id)
                        ->all();
                }
            },
        ], parent::filters($request));
    }
}
