<?php

namespace App\Domains\OrderFulfillment\Nova;

use Illuminate\Http\Request;

class Order extends Resource
{
    public static $model = \App\Domains\OrderFulfillment\Models\Order::class;

    public static $title = 'name';

    public static $search = [
        'id',
    ];

    public function fields(Request $request): array
    {
        return [];
    }
}
