<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CheckOverdueTransactions::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Run daily at midnight to check for overdue transactions
        $schedule->command('app:process-penalties')
                 ->daily()
                 ->timezone('Asia/Manila'); // Adjust to your timezone

        // For testing, you can run it every minute:
        // $schedule->command('transactions:check-overdue')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}