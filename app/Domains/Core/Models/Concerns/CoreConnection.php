<?php

namespace App\Domains\Core\Models\Concerns;

trait CoreConnection
{
    public function getConnectionName(): string
    {
        return 'core';
    }
}
