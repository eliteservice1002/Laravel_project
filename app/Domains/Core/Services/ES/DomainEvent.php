<?php

namespace App\Domains\Core\Services\ES;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class DomainEvent implements Event
{
    protected static array $eventNames = [];

    protected string $eventName;

    public function eventName(): string
    {
        if ( ! isset(static::$eventNames[static::class])) {
            static::$eventNames[static::class] = $this->eventName ?? static::guessEventName();
        }

        return static::$eventNames[static::class];
    }

    protected static function guessEventName(): string
    {
        $segments = Collection::wrap(explode('\\', static::class))
            ->filter(fn ($value) => ! in_array($value, ['App', 'Domains', 'Events']))
            ->map(fn ($value) => Str::snake($value))
            ->all();

        return implode('.', $segments);
    }
}
