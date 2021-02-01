<?php

namespace App\Domains\Core\Subscribers;

use App\Domains\Core\Models\StoredEvent;
use App\Domains\Core\Services\ES\Event;
use Illuminate\Contracts\Events\Dispatcher;

class StoredEventSubscriber
{
    public function subscribe(Dispatcher $events): void
    {
        $events->listen('*', static::class.'@handle');
    }

    public function handle(string $eventName, $payload): void
    {
        if ( ! $this->shouldHandleEvent($eventName)) {
            return;
        }

        $this->storeEvent($payload[0]);
    }

    protected function shouldHandleEvent(string $event): bool
    {
        if ( ! class_exists($event)) {
            return false;
        }

        return is_subclass_of($event, Event::class);
    }

    protected function storeEvent($event): void
    {
        StoredEvent::store($event);
    }
}
