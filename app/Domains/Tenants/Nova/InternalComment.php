<?php

namespace App\Domains\Tenants\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class InternalComment extends Resource
{
    public static $model = \App\Domains\Tenants\Models\InternalComment::class;

    public static $displayInNavigation = false;

    public static $title = 'id';

    public function fields(Request $request): array
    {
        return [
            Textarea::make('comment')
                ->alwaysShow()
                ->hideFromIndex(),

            MorphTo::make('Commentable')
                ->onlyOnIndex(),

            MorphTo::make('Author')
                ->exceptOnForms(),

            Text::make('comment')
                ->displayUsing(fn ($comment) => Str::limit($comment, 255))
                ->onlyOnIndex(),

            DateTime::make('Created', 'created_at')
                ->format('Y-m-d')
                ->exceptOnForms()
                ->sortable(),
        ];
    }
}
