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
        /**
         * Crons Example:
         */
        // $schedule->command('command')->everyMinute()->when(function () {
        //     return Crons::everySomeMinutes('command', 10);
        // });
        // $schedule->command('command')->everyMinute()->when(function () {
        //     return Crons::dailyAt('command', 'time');
        // });
        // $schedule->command('command')->everyMinute()->when(function () {
        //     return Crons::weeklyAt('command', 'days', 'time');
        // });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
