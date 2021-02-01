<?php

namespace App\Domains\ProductCatalog\Nova;

use App\Domains\Marketing\Nova\Slug;
use Devpartners\AuditableLog\AuditableLog;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Text;
use OptimistDigital\NovaSortable\Traits\HasSortableManyToManyRows;
use Spatie\NovaTranslatable\Translatable;

class ProductAttributeOption extends Resource
{
    use HasSortableManyToManyRows;

    public static $model = \App\Domains\ProductCatalog\Models\ProductAttributeOption::class;

    public static $displayInNavigation = false;

    public static $title = 'name';

    public static $search = [
        'code',
        'name',
    ];

    public function fields(Request $request): array
    {
        return [
            Text::make(__('Code'), 'code')
                ->sortable(),

            BelongsTo::make('Attribute', 'attribute', ProductAttribute::class),

            Translatable::make([
                Text::make('Name')
                    ->required(),

                Text::make('Slug')
                    ->required()
                    ->hideWhenCreating()
                    ->hideFromIndex(),
            ]),

            new Tabs('Related', [
                MorphMany::make('Slugs', 'slugs', Slug::class),
            ]),

            AuditableLog::make(),
        ];
    }
}
