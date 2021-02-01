<?php

namespace App\Domains\Core\Models\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Ulid\Ulid;

/**
 * @property string $ulid
 */
trait HasUlid
{
    protected string $ulidColumn = 'ulid';

    public function getRouteKeyName(): string
    {
        return $this->ulidColumn;
    }

    public function initializeHasUlid(): void
    {
        if ( ! isset($this->casts['ulid'])) {
            $this->casts[$this->ulidColumn] = 'string';
        }

        $this->{$this->ulidColumn} = (string) Ulid::generate();
    }

    public static function addUlidColumn(Blueprint $table, string $name = 'ulid', bool $unique = true): ColumnDefinition
    {
        $column = $table->char($name, 26);

        if ($unique) {
            $column->unique();
        }

        return $column;
    }
}
