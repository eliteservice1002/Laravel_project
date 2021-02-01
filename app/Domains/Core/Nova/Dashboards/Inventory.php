<?php

namespace App\Domains\Core\Nova\Dashboards;

use Laravel\Nova\Dashboard;

class Inventory extends Dashboard
{
    public function cards(): array
    {
        return [];
    }

    public static function uriKey(): string
    {
        return 'inventory';
    }
}
