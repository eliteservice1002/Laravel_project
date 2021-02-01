<?php

namespace App\Domains\Marketing\Nova;

use App\Domains\Tenants\Nova\Store;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;

class ContentPage extends Resource
{
    public static $model = \App\Domains\Marketing\Models\ContentPage::class;

    public static function label()
    {
        return 'Pages';
    }

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'))->sortable(),

            BelongsTo::make('Store', 'store', Store::class),

            HasMany::make('Blocks', 'blocks', ContentPageBlock::class),
        ];
    }
}
