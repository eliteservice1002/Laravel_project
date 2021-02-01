<?php

namespace App\Domains\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

abstract class CorePivot extends Pivot
{
    use HasFactory;

    public function getTable(): string
    {
        if ( ! isset($this->table)) {
            $this->setTable(str_replace('\\', '', Str::snake(Str::plural(class_basename($this)))));
        }

        return $this->table;
    }

    public static function getTableName(): string
    {
        return str_replace('\\', '', Str::snake(Str::plural(class_basename(static::class))));
    }
}
