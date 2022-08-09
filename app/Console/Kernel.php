<?php

namespace App\Console;

use App\Tasks\MailingFinisherTask;
use App\Tasks\MailSenderTask;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
         * On each call, check for waiting mails and send up to 10 mails on each run, important is not sending more than 600 mails in an hour.
         * 
         * Run cron every minute: * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
         * 
         * Run cron every hour: 0 * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
         * 
         */
        $schedule->call(function () {

            (new MailSenderTask())();
            (new MailingFinisherTask())();

        })->everyMinute()->timezone('Europe/Zurich')->appendOutputTo(storage_path('logs/scheduler.log'));
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
