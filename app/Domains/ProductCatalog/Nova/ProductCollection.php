<?php

namespace App\Domains\ProductCatalog\Nova;

use App\Domains\Marketing\Nova\Slug;
use App\Domains\ProductCatalog\Nova\Actions\Publish;
use App\Domains\ProductCatalog\Nova\Actions\Unpublish;
use Devpartners\AuditableLog\AuditableLog;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use OptimistDigital\NovaSortable\Traits\HasSortableManyToManyRows;
use Spatie\NovaTranslatable\Translatable;
use Spatie\TagsField\Tags;

/**
 * @property \App\Domains\ProductCatalog\Models\ProductCollection $resource
 */
class ProductCollection extends Resource
{
    use HasSortableManyToManyRows;

    public static $model = \App\Domains\ProductCatalog\Models\ProductCollection::class;

    public static $title = 'name';

    public static $search = [
        'name',
    ];

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            // BelongsTo::make('Store', 'store', Store::class)
            //     ->searchable()
            //     ->hideFromIndex()
            //     ->hideWhenUpdating(),

            Translatable::make([
                Text::make('Name')->required(),

                Trix::make('Description')->required(),
            ]),

            Tags::make('Tags')
                ->type('product_collection'),

            new Tabs('Related', [
                HasMany::make('Items', 'items', ProductCollectionItem::class),

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
        ], parent::actions($request));
    }
}
