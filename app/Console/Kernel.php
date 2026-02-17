<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Fetch 10 new memes daily at 3 AM
        $schedule->command('memes:fetch-imgflip --limit=10')
                 ->daily()
                 ->at('03:00');

                 
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
    \App\Console\Commands\FetchIndianMemes::class,
    \App\Console\Commands\FetchImgflipMemes::class, // existing
];
}