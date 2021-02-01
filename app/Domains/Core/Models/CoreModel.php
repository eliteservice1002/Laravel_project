<?php

namespace App\Domains\Core\Models;

use App\Domains\Core\Models\Concerns\CoreConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

abstract class CoreModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CoreConnection;

    public static function getTableName(): string
    {
        return Str::snake(Str::pluralStudly(class_basename(static::class)));
    }
}
