<?php

namespace App\Domains\OrderFulfillment\Nova;

use Illuminate\Http\Request;

class ReturnRequest extends Resource
{
    public static $model = \App\Domains\OrderFulfillment\Models\ReturnRequest::class;

    public static $title = 'name';

    public static $search = [
        'id',
    ];

    public function fields(Request $request): array
    {
        return [];
    }
}
