<?php

namespace App\Domains\Core\Providers;

use Illuminate\Foundation\Events\DiscoverEvents;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Discover the events and listeners for the application.
     */
    public function discoverEvents(): array
    {
        return collect($this->discoverEventsWithin())
            ->reject(function ($directory) {
                return ! is_dir($directory);
            })
            ->reduce(function ($discovered, $directory) {
                return array_merge_recursive(
                    $discovered,
                    DiscoverEvents::within($directory, base_path())
                );
            }, []);
    }

    /**
     * Get the listener directories that should be used to discover events.
     */
    protected function discoverEventsWithin(): array
    {
        return [
            app_path('Listeners'),
        ];
    }
}
