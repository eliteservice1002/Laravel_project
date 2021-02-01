<?php

namespace App\Domains\Core\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * @var callable[]
     */
    protected array $schedules = [];

    /**
     * The Artisan commands provided by the application.
     *
     * @var string[]
     */
    protected $commands = [];

    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('media-library:delete-old-temporary-uploads')->daily();

        collect($this->schedules)->each(fn ($scheduled) => $scheduled($schedule));
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
