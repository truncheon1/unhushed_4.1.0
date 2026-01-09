<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    //The Artisan commands provided by your application.
    protected $commands = [
        Commands\CartAbandoned::class,
        Commands\CartShipped::class,
        Commands\DailyUpdate::class,
        //Commands\ProductCheck::class [in production]
        Commands\NormalizeProductImageSorts::class,
        Commands\MoveCurriculaFiles::class,
    ];

    /**
     * Define the application's command schedule.
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('ac:abandoned')
             ->everyThirtyMinutes()
             ->withoutOverlapping();

        $schedule->command('ac:shipped')
             ->everyThirtyMinutes()
             ->withoutOverlapping();

        $schedule->command('ac:update')->daily();

        $schedule->command('products:check')->daily();

        // Process queued jobs efficiently
        // Use max-jobs to prevent memory bloat, max-time to prevent long-running processes
        $schedule->command('queue:work database --max-jobs=100 --max-time=3600 --stop-when-empty')
             ->everyMinute()
             ->withoutOverlapping();
    }
    // cd /home/u638-lto3fgvnhiat/www/unhushed.org && php artisan schedule:run

    /**
     * Register the commands for the application.
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
