<?php

namespace App\Console\Commands;

use App\Classes\Moka;
use App\Classes\Telegram;
use App\Models\MokaAutoPayment;
use App\Models\MokaRefund;
use Exception;
use Illuminate\Console\Command;

class CheckMokaPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ruzgarnet:checkMokaPlan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check auto payments from moka.';

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
            $plans = MokaAutoPayment::whereNotIn('status', [1, 3, 5, 6])->get();

            foreach ($plans as $plan) {
                $payment_plan = $moka->get_payment_plan($plan->moka_plan_id);

                if ($payment_plan->Data != null) {
                    $plan->status = $payment_plan->Data->PlanStatus;
                    $plan->save();

                    if (
                        $payment_plan->Data->PlanStatus == 1 &&
                        $plan->payment->status == 2 &&
                        $plan->payment->type != 5 && 
                        !$plan->isRefund()
                    ) {
                        $payment_detail = $moka->get_payment_detail($payment_plan->Data->DealerPaymentId);

                        if (
                            isset($payment_detail->Data->PaymentDetail->OtherTrxCode) && 
                            !empty($payment_detail->Data->PaymentDetail->OtherTrxCode)
                        ) {
                            $result = $moka->do_void(
                                $payment_detail->Data->PaymentDetail->OtherTrxCode
                            );

                            $success = false;
                            if ($result->Data != null && isset($result->Data->IsSuccessful) && (bool)$result->Data->IsSuccessful)
                                $success = true;

                            $plan->status = 5;
                            $plan->save();

                            MokaRefund::create([
                                'payment_id' => $plan->payment->id,
                                'auto_payment_id' => $plan->id,
                                'price' => $plan->payment->price,
                                'status' => $success
                            ]);
                        }
                    } else if ($payment_plan->Data->PlanStatus == 0) {
                        $plan->status = 6;
                        $plan->save();
                    } else {
                        if ($payment_plan->Data->PlanStatus == 1) {
                            $plan->payment->receive([
                                'type' => 5
                            ]);
                        }
                    }
                } else {
                    $plan->status = 4;
                    $plan->save();
                }
            }

            $this->info('Auto payments checked.');
        } catch (Exception $e) {
            Telegram::send(
                'Test',
                'Command - Check Auto Payment : ' . $e->getMessage()
            );
        }
    }
}
