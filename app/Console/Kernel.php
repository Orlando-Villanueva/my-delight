<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Monitor cache hit rate every hour
        $schedule->command('monitor:cache-hit-rate --alert-threshold=50')
                 ->hourly()
                 ->appendOutputTo(storage_path('logs/cache-monitor.log'));
        
        // Prune Telescope entries daily
        $schedule->command('telescope:prune --hours=24')
                 ->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}