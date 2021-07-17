<?php

namespace App\Jobs;

use App\Classes\Messages;
use App\Classes\SMS_Api;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckPaymentsForSMS implements ShouldQueue
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
        if ($this->check('CheckPaymentsForSMS')) {
            $sms = new SMS_Api();

            $message_not_paid = Message::find(5);
            $message_paid = Message::find(6);

            $messages = [];
            $message_formatter = new Messages();

            $payments = Payment::where('date', date('Y-m-15'))->get();
            foreach ($payments as $payment) {
                if ($payment->status == 2) {
                    $messages[] = [
                        $payment->subscription->customer->telephone,
                        $message_formatter->generate(
                            $message_paid->message,
                            [
                                'ad_soyad' => $payment->subscription->customer->full_name,
                                'tarih' => date('d/m/Y', strtotime($payment->date)),
                                'tutar' => $payment->price
                            ]
                        )
                    ];
                } else {
                    $messages[] = [
                        $payment->subscription->customer->telephone,
                        $message_formatter->generate(
                            $message_not_paid->message,
                            [
                                'ad_soyad' => $payment->subscription->customer->full_name,
                                'tarih' => date('d/m/Y', strtotime($payment->date)),
                                'tutar' => $payment->price
                            ]
                        )
                    ];
                }
            }

            $sms->submitMulti(
                "RUZGARNET",
                $messages
            );

            $this->insertJob('CheckPaymentsForSMS');
        }
    }
}
