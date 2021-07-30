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

class NotPaidedSMS implements ShouldQueue
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
            if ($this->check('NotPaidedSMS')) {
                $sms = new SMS_Api();

                $message = Message::find(9);

                $messages = [];
                $message_formatter = new Messages();

                $messages[] = [
                    '5325835151',
                    $message_formatter->generate(
                        $message->message,
                        [
                            'ad_soyad' => 'İbrahim Küçüksüslü',
                            'ay' => date('m'),
                            'yil' => date('Y')
                        ]
                    )
                ];

                $payments = Payment::where('status', '<>', 2)->where('date', date('Y-m-15'))->get();
                foreach ($payments as $payment) {
                    $messages[] = [
                        $payment->subscription->customer->telephone,
                        $message_formatter->generate(
                            $message->message,
                            [
                                'ad_soyad' => $payment->subscription->customer->full_name,
                                'ay' => date('m'),
                                'yil' => date('Y')
                            ]
                        )
                    ];
                }

                $sms->submitMulti(
                    "RUZGARNET",
                    $messages
                );

                $this->insertJob('NotPaidedSMS');
            }
        } catch (Exception $e) {
            Telegram::send('Test', 'NotPaidedSMS Job - ' . $e->getMessage());
        }
    }
}
