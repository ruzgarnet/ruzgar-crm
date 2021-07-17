<?php

namespace App\Console;

use App\Jobs\CheckAutoPayments;
use App\Jobs\CheckHalfPayments;
use App\Jobs\CheckMessageList;
use App\Jobs\CheckPayments;
use App\Jobs\CheckPaymentsForSMS;
use App\Jobs\CheckPenalties;
use App\Jobs\CheckSMSList;
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
        // FIXME REFUSE MULTIPLE JOBS
        $date = Carbon::now();

        $schedule->job(new CheckMessageList);

        if ($date->hour >= 10) {
            // Fatura oluşturuldu
            if ($date->day == 1 || $date->day == 10) {
                $schedule->job(new CheckPayments);
            }

            // Yarı fatura oluşturuldu
            if ($date->day == 10) {
                $schedule->job(new CheckHalfPayments);
            }

            // Ceza ödemeleri kontrol edildi
            if ($date->day == 23) {
                $schedule->job(new CheckPenalties);
            }
        }

        if(($date->hour > 12 && $date->hour < 14) && $date->minute > 29)
        {
            if($date->day == 15)
            {
                $schedule->job(new CheckPayments);
            }
            if($date->day == 16)
            {
                $schedule->job(new CheckPaymentsForSMS);
            }
        }

        if($date->hour == 22)
        {
            // Otomatik ödemeler Moka'ya aktarıldı
            if ($date->day == 14) {
                $schedule->job(new CreateAutoPayments);
            }
        }

        if(in_array($date->day, [15, 16, 17, 18]) && ($date->hour == 19))
        {
            $schedule->job(new CheckAutoPayments);
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
