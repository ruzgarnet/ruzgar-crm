<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\PaymentPriceEdit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreatePenaltyPrices implements ShouldQueue
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
        if ($this->check('CreatePenaltyPrices')) {
            $payments = Payment::where("status", "<>", 2)->where('date', date('Y-m-15'))->get();

            foreach ($payments as $payment) {
                $new_price = $payment->price + setting('payment.penalty.price', 44.9);

                PaymentPriceEdit::create([
                    'payment_id' => $payment->id,
                    'staff_id' => null,
                    'old_price' => $payment->price,
                    'new_price' => $new_price,
                    'description' => trans('response.system.penalty', ['price' => setting('payment.penalty.price', 44.9)])
                ]);

                DB::table('payment_penalties')->insert([
                    'payment_id' => $payment->id,
                    'old_price' => $payment->price,
                    'new_price' => $new_price
                ]);

                $payment->price = $new_price;
                $payment->save();
            }

            $this->insertJob('CreatePenaltyPrices');
        }
    }
}
