<?php

namespace App\Domains\Core\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * @property bool $sequentialCodeOnCreation
 * @property int $sequentialCodePaddingLength
 * @property string $sequentialCodePlaceholder
 * @property int $sequentialCodeFallback
 *
 * @method static \Illuminate\Database\Eloquent\Builder query()
 */
trait HasSequentialCode
{
    public static array $prefixMap = [];

    public static function bootHasSequentialCode(): void
    {
        $prefix = Str::upper(implode('', array_map(
                fn ($s) => substr($s, 0, 1), explode('_', Str::snake(class_basename(static::class))),
            ))).'-';

        static::$prefixMap[static::class] = $prefix;

        static::creating(function ($model) {
            $column = $model->getSequentialCodeColumn();

            if ($model->getSequentialCodeOnCreation() && $model->{$column} === $model->getSequentialCodePlaceholder()) {
                $model->setSequentialCode();
            }
        });
    }

    public static function firstOrFailByCode(string $code): static
    {
        return static::query()
            ->where('code', $code)
            ->firstOrFail();
    }

    public function setSequentialCode(string $code = null): void
    {
        $code ??= $this->getSequentialCodeHighest();

        $code = Str::padLeft($code, $this->getSequentialCodePaddingLength(), '0');

        $columnName = $this->getSequentialCodeColumn();

        $this->{$columnName} = static::getSequentialCodePrefix($this).$code;
    }

    public static function getSequentialCodePrefix(self $instance): string
    {
        return static::$prefixMap[static::class];
    }

    protected function getCodeAttribute(?string $value): string
    {
        if ($value) {
            return $value;
        }

        return '---';
    }

    protected function getSequentialCodeHighest(): int
    {
        $highest = $this->getSequentialCodeQuery()
            ->max($this->getSequentialCodeColumn());

        if (is_null($highest)) {
            return $this->getSequentialCodeFallback();
        }

        return (int) Str::after($highest, static::getSequentialCodePrefix($this)) + 1;
    }

    protected function getSequentialCodeColumn(): string
    {
        return 'code';
    }

    protected function getSequentialCodeOnCreation(): bool
    {
        return $this->sequentialCodeOnCreation ?? true;
    }

    protected function getSequentialCodePaddingLength(): int
    {
        return $this->sequentialCodePaddingLength ?? 6;
    }

    protected function getSequentialCodePlaceholder(): string
    {
        return $this->sequentialCodePlaceholder ?? '---';
    }

    protected function getSequentialCodeQuery(): Builder
    {
        return static::query()
            ->where($this->getSequentialCodeColumn(), 'LIKE', static::getSequentialCodePrefix($this).'%');
    }

    protected function getSequentialCodeFallback(): int
    {
        return $this->sequentialCodeFallback ?? 0;
    }
}
