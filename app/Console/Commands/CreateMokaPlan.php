<?php

namespace App\Console\Commands;

use App\Classes\Moka;
use App\Classes\Telegram;
use App\Models\MokaAutoPayment;
use App\Models\MokaSale;
use Exception;
use Illuminate\Console\Command;

class CreateMokaPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ruzgarnet:createMokaPlan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create moka plans.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $moka = new Moka();

            $moka_sales = MokaSale::whereNull("disabled_at")->get();
            foreach ($moka_sales as $sale) {
                $payment = $sale->subscription->currentPayment();
                if ($payment) {
                    if (!$payment->isPaid() && !$payment->mokaAutoPayment()->count()) {
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
            }

        } catch (Exception $e) {
            Telegram::send(
                'Test',
                'Command - Create Moka Plan : ' . $e->getMessage()
            );
        }

        $this->info('Added moka plans.');
    }
}
