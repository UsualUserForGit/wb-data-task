<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Определение комманд
     */
    protected $commands = [
        Commands\FetchApiData::class,
    ];
    /**
     * Определение расписания комманд приложения
     */
    protected function schedule(Schedule $schedule): void
    {
        // Два раза в день, в 9:00 и 21:00
        $schedule->command('fetch:api-data orders --accountId=5 --key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie --limit=50')->twiceDaily(9, 21);
        $schedule->command('fetch:api-data stocks --accountId=5 --key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie --limit=50')->twiceDaily(9, 21);
        $schedule->command('fetch:api-data incomes --accountId=5 --key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie --limit=50')->twiceDaily(9, 21);
        $schedule->command('fetch:api-data sales --accountId=5 --key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie --limit=50')->twiceDaily(9, 21);
    }

    /**
     * Регистрация комманд в приложении
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
