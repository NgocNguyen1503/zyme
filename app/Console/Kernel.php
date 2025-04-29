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
        // $schedule->command('inspire')->hourly();
        // Process command run everyday at 00:00
        $schedule->command('app:salary')->daily();
        // Neu muon cai dat de chay vao 1 thoi diem cu the thi thay bang dau * va so (phut gio ngay thang tuan)
        $schedule->command('app:salary')->cron('* * * * *');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
