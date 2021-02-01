<?php

namespace App\Domains\Core\Models;

use App\Domains\Core\Models\Concerns\TenantConnection;

class Media extends \Spatie\MediaLibrary\MediaCollections\Models\Media
{
    use TenantConnection;

    public array $sortable = ['order_column_name' => 'sort_order'];
}
