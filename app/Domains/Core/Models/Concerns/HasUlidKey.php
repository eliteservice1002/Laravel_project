<?php

namespace App\Domains\Core\Models\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;

trait HasUlidKey
{
    use HasUlid;

    public function initializeHasUlidKey(): void
    {
        $this->setKeyType('string');
        $this->setIncrementing(false);
        $this->ulidColumn = 'id';
    }

    public static function addPrimaryUlidColumn(Blueprint $table, string $name = 'id'): ColumnDefinition
    {
        return static::addUlidColumn($table, $name, false)->primary();
    }
}
