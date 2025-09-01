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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
 protected function schedule(Schedule $schedule)
    {
        // Membersihkan history log setiap bulan pada tanggal 1 jam 00:00
        $schedule->command('logs:clean')
                 ->monthlyOn(1, '00:00')
                 ->before(function () {
                     \Log::info('Scheduled history log cleanup starting...');
                 })
                 ->onSuccess(function () {
                     \Log::info('Scheduled history log cleanup completed successfully.');
                 })
                 ->onFailure(function () {
                     \Log::error('Scheduled history log cleanup failed.');
                 })
                 ->after(function () {
                     \Log::info('Scheduled history log cleanup finished.');
                 });
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
