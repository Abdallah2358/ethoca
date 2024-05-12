<?php

namespace App\Console;

// use Http;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->everyFiveSeconds()->output();
        // $schedule->command('echo hello')->everyFiveSeconds();
        // $schedule->call(
        //     function () {
        //         $response = Http::get('http://localhost:8000');
        //         echo $response->json();
        //     }
        // )->appendOutputTo('scheduler-output.log');
        $schedule->command('app:make-http-request')->everyFiveSeconds()->appendOutputTo('test.log');

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
