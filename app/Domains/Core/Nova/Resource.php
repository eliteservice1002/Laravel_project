<?php

namespace App\Domains\Core\Nova;

// use ChrisWare\NovaBreadcrumbs\Traits\Breadcrumbs;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;
use Titasgailius\SearchRelations\SearchesRelations;

abstract class Resource extends NovaResource
{
    // use Breadcrumbs;
    use SearchesRelations;

    public static $model;

    public static $group = '9 - Other';

    public static $preventFormAbandonment = true;

    public static $perPageViaRelationship = 25;

    protected static bool $shouldSoftDelete = false;

    public static function softDeletes(): bool
    {
        return static::$shouldSoftDelete;
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    public static function scoutQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query);
    }

    public static function relatableQuery(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query);
    }

    // public function actions(Request $request)
    // {
    //     return [
    //         new DownloadExcel(),
    //     ];
    // }

    public static function redirectAfterCreate(NovaRequest $request, $resource): string
    {
        if ($request->get('viaResource')) {
            return "/resources/{$request->get('viaResource')}/{$request->get('viaResourceId')}";
        }

        return parent::redirectAfterCreate($request, $resource);
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource): string
    {
        if ($request->get('viaResource')) {
            return "/resources/{$request->get('viaResource')}/{$request->get('viaResourceId')}";
        }

        return parent::redirectAfterUpdate($request, $resource);
    }
}
