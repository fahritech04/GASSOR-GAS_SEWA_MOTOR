<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Sync payment status setiap 30 menit untuk transaksi pending
        $schedule->command('midtrans:sync-status')
                 ->everyThirtyMinutes()
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/midtrans-sync.log'));
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
