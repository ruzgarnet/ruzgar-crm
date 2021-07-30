<?php

namespace App\Console\Commands;

use App\Classes\Messages;
use App\Classes\SMS_Api;
use App\Models\Message;
use App\Models\Payment;
use Illuminate\Console\Command;

class PaymentMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ruzgarnet:paymentMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check payments and add messages.';

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

        $this->info('Added payment warning messages.');
    }
}
