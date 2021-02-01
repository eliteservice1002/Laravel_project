<?php

namespace App\Domains\Marketing\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Spatie\NovaTranslatable\Translatable;

class ContentPageBlock extends Resource
{
    public static $model = \App\Domains\Marketing\Models\ContentPageBlock::class;

    public static $displayInNavigation = false;

    public static function label()
    {
        return 'Page Blocks';
    }

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'))->sortable(),

            BelongsTo::make('Page', 'page', ContentPage::class)
                ->hideWhenCreating(),

            Select::make('Type')
                ->options([
                    'featured-carousel' => 'Featured Carousel',
                    'item-set' => 'Item Set',
                    'announcement-banner' => 'Announcement Banner',
                ]),

            Translatable::make([
                Text::make('Label'),

                Text::make('Show More Label'),
            ]),

            HasMany::make('Items', 'items', ContentPageBlockItem::class),
        ];
    }
}
