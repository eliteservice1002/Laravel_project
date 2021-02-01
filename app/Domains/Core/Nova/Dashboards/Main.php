<?php

namespace App\Domains\Core\Nova\Dashboards;

use Laravel\Nova\Dashboard;

class Main extends Dashboard
{
    public function cards(): array
    {
        return [];
    }

    public static function uriKey(): string
    {
        return 'johrh';
    }
}
