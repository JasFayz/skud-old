<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Booking\Console\Commands\DeactivateBooking;
use Modules\Report\Commands\MakeDailyAttendanceLog;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(new DeactivateBooking)->everyMinute();
        $schedule->command("attendance:log")->dailyAt("23:00");
        $schedule->command('sanctum:prune-expired --hours=24')->daily();
        $schedule->command('telescope:prune')->daily();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
