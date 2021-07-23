<?php

namespace App\Jobs;

use App\Classes\Messages;
use App\Classes\SMS_Api;
use App\Models\Message;
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
        try {
            $messages = [];
            $message_formatter = new Messages();
            $sms = new SMS_Api();
            $message = Message::find(7);

            if ($this->check('CreatePenaltyPrices')) {
                $payments = Payment::where("status", "<>", 2)->where('date', date('Y-m-15'))->get();

                foreach ($payments as $payment) {
                    if (!$payment->isPenalty()) {
                        $new_price = $payment->price + setting('payment.penalty.price', 44.9);

                        PaymentPriceEdit::create([
                            'payment_id' => $payment->id,
                            'staff_id' => null,
                            'old_price' => $payment->price,
                            'new_price' => $new_price,
                            'description' => trans('response.system.penalty', ['price' => setting('payment.penalty.price', 44.9)])
                        ]);

                        DB::table('payment_penalties')->insert([
                            'payment_id' => $payment->id
                        ]);

                        $payment->price = $new_price;
                        $payment->penalty = 1;
                        $payment->save();

                        $messages[] = [
                            $payment->subscription->customer->telephone,
                            $message_formatter->generate(
                                $message->message,
                                [
                                    'ay' => date('m'),
                                    'yil' => date('Y'),
                                    'ad_soyad' => $payment->subscription->customer->full_name,
                                    'tarih' => date('d/m/Y', strtotime($payment->date))
                                ]
                            )
                        ];
                    }
                }

                $sms->submitMulti(
                    "RUZGARNET",
                    $messages
                );

                $this->insertJob('CreatePenaltyPrices');
            }
        } catch (Exception $e) {
            Telegram::send('Test', "Create Penalty Price Job \n" . $e->getMessage());
        }
    }
}
