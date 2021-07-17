<?php

namespace App\Jobs;

use App\Classes\Moka;
use App\Classes\Telegram;
use App\Models\MokaAutoPayment;
use App\Models\MokaSale;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateAutoPayments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CheckJob;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->check('CreateAutoPayments')) {
            try {
                $moka = new Moka();

                $moka_sales = MokaSale::whereNull("disabled_at")->get();
                foreach ($moka_sales as $sale) {
                    $payment = $sale->subscription->currentPayment();
                    if($payment)
                    {
                        if ($payment->status != 2 && !$payment->mokaAutoPayment()->count()) {
                            $result = $moka->add_payment_plan(
                                $sale->moka_sale_id,
                                date('Ymd', strtotime(' + 1 day')),
                                $payment->price
                            );

                            if (isset($result->Data->DealerPaymentPlanId)) {
                                MokaAutoPayment::create([
                                    'sale_id' => $sale->id,
                                    'payment_id' => $payment->id,
                                    'moka_plan_id' => $result->Data->DealerPaymentPlanId
                                ]);
                            }
                        }
                    }
                    else
                    {
                        Telegram::send(
                            'Test',
                            'Otomatik Eksik : ' . $sale->subscription_id
                        );
                    }
                }

                $this->insertJob('CreateAutoPayments');
            } catch (Exception $e) {
                Telegram::send(
                    'Test',
                    'Otomatik Hata : ' . $e->getMessage()
                );
            }
        }
    }
}
