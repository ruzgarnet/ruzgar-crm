<?php

namespace App\Console;

use App\Jobs\CheckHalfPayments;
use App\Jobs\CheckPayments;
use App\Jobs\CheckPenalties;
use App\Jobs\CreateAutoPayments;
use App\Jobs\CreatePenaltyPrices;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

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
        $date = Carbon::now();

        if ($date->day == 1) {
            $schedule->job(new CheckPayments);
        }

        if ($date->day == 10) {
            $schedule->job(new CheckHalfPayments);
        }

        if ($date->day == 23) {
            $schedule->job(new CheckPenalties);
        }

        // Send auto payment plans to Moka
        if ($date->day == 15) {
            $schedule->job(new CreateAutoPayments);
        }

        // Add penalty prices
        $payment_penalty_date = setting("payment.penalty.day", 23);
        if (!is_numeric($payment_penalty_date)) {
            $payment_penalty_date = 23;
        }
        if ($date->day == $payment_penalty_date) {
            $schedule->job(new CreatePenaltyPrices);
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
