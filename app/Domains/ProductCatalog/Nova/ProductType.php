<?php

namespace App\Domains\ProductCatalog\Nova;

use App\Domains\Marketing\Nova\Slug;
use Devpartners\AuditableLog\AuditableLog;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Text;
use OptimistDigital\NovaSortable\Traits\HasSortableManyToManyRows;
use Spatie\NovaTranslatable\Translatable;

class ProductType extends Resource
{
    use HasSortableManyToManyRows;

    public static $model = \App\Domains\ProductCatalog\Models\ProductType::class;

    public static $title = 'name';

    public static $search = [
        'code',
        'name',
    ];

    public function fields(Request $request): array
    {
        return [
            Text::make('Code')
                ->required()
                ->rules('unique:'.\App\Domains\ProductCatalog\Models\ProductType::class)
                ->sortable(),

            Translatable::make([
                Text::make('Name')
                    ->required(),
            ]),

            new Tabs('Related', [
                BelongsToMany::make('Attributes', 'attributes', ProductAttribute::class),

                MorphMany::make('Slugs', 'slugs', Slug::class),
            ]),

            AuditableLog::make(),
        ];
    }
}
