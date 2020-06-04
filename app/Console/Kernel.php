<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\WordOfTheDay::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // test line to invoke a Job through the Laravel scheduler
        // $schedule->call(function () {
        //   \App\Jobs\TradFtHeadcountByTypes::dispacth();
        // })->dailyAt('01:00');


        // $schedule->command('word:day')->daily();
        // $schedule->command('inspire')->hourly();

        // $schedule->command('word:day')->everyFiveMinutes();
        // scheduling reference
        // https://laravel.com/docs/7.x/scheduling#scheduling-artisan-commands
        $schedule->command('word:day')->dailyAt('13:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
