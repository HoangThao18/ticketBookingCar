<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\GetListMoMo::class,
        Commands\Bank\GetListBank::class,
        Commands\Bank\CheckBank::class,
        Commands\Bank\GetTokenBank::class,
        Commands\CancelBillSpending::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        //  $schedule->command('get-list:momo')->everyMinute();
         $schedule->command('bank:list')->everyMinute();
         $schedule->command('bank:check')->everyMinute();
         $schedule->command('bank:get-token')->everySixHours();
         $schedule->command('cancel-bill-spending')->everyMinute();
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
