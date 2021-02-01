<?php

namespace App\Domains\ProductCatalog\Nova;

use App\Domains\Marketing\Nova\Slug;
use Devpartners\AuditableLog\AuditableLog;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Text;
use OptimistDigital\NovaSortable\Traits\HasSortableManyToManyRows;
use Spatie\NovaTranslatable\Translatable;

/**
 * @property \App\Domains\ProductCatalog\Models\ProductAttribute $resource
 */
class ProductAttribute extends Resource
{
    use HasSortableManyToManyRows;

    public static $model = \App\Domains\ProductCatalog\Models\ProductAttribute::class;

    public static $search = [
        'code',
        'name',
    ];

    public function title(): string
    {
        return "[{$this->resource->code}] {$this->resource->name}";
    }

    public function fields(Request $request): array
    {
        return [
            Text::make('Code')
                ->required()
                ->rules([
                    Rule::unique(\App\Domains\ProductCatalog\Models\ProductType::class, 'code')
                        ->whereNot('id', $this->resource->id),
                ])
                ->sortable(),

            Translatable::make([
                Text::make('Name')
                    ->required(),
            ]),

            new Tabs('Related', [
                HasMany::make('Options', 'options', ProductAttributeOption::class),

                BelongsToMany::make('Product Types', 'types', ProductType::class),

                MorphMany::make('Slugs', 'slugs', Slug::class),
            ]),

            AuditableLog::make(),
        ];
    }
}
