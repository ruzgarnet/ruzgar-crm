<?php

namespace App\Jobs;

use App\Classes\Moka;
use App\Classes\Telegram;
use App\Models\MokaAutoPayment;
use App\Models\MokaRefund;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckAutoPayments implements ShouldQueue
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
        if (true) {
            try {
                $moka = new Moka();
                $plans = MokaAutoPayment::where('status', 0)->offset(0)->limit(300)->get();

                foreach ($plans as $plan) {
                    $payment_plan = $moka->get_payment_plan($plan->moka_plan_id);

                    if ($payment_plan->Data != null) {
                        $plan->status = $payment_plan->Data->PlanStatus;
                        $plan->save();

                        if (
                            $payment_plan->Data->PlanStatus == 1 &&
                            $plan->payment->status == 2 &&
                            $plan->payment->type != 5
                        ) {
                            $payment_detail = $moka->get_payment_detail($payment_plan->Data->DealerPaymentId);
                            dump($payment_detail);
                            if (isset($payment_detail->Data->PaymentDetail->OtherTrxCode) && !empty($payment_detail->Data->PaymentDetail->OtherTrxCode)) {
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
                        }
                        else if($payment_plan->Data->PlanStatus == 0)
                        {
                            $plan->status = 6;
                            $plan->save();
                        }
                        else {
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
                $this->insertJob('CheckAutoPayments');
            } catch (Exception $e) {
                Telegram::send(
                    "Test",
                    $e->getMessage()
                );
            }
        }
    }
}
