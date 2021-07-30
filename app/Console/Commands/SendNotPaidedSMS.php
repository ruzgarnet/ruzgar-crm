<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendNotPaidedSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ruzgarnet:sendNotPaided';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send warning messages for not paided payments.';

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
            $sms = new SMS_Api();

            $message = Message::find(9);

            $messages = [];
            $message_formatter = new Messages();

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
        } catch (Exception $e) {
            Telegram::send('Test', 'SendNotPaidedSMS Command - ' . $e->getMessage());
        }

        $this->info('Added payment penalty warning messages.');
    }
}
