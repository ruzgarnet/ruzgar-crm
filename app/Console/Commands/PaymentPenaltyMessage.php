<?php

namespace App\Console\Commands;

use App\Classes\Messages;
use App\Classes\SMS_Api;
use App\Models\Message;
use App\Models\Subscription;
use Illuminate\Console\Command;

class PaymentPenaltyMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ruzgarnet:paymentPenaltyMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment penalty messages.';

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
        $messages = [];

        $message = Message::find(2);

        $subscriptions = Subscription::join('payments', 'subscriptions.id', 'payments.subscription_id')
            ->where('payments.status', '<>', 2)
            ->where('payments.date', date('Y-m-15'))
            ->get();

        $messages = (new Messages())->multiMessage(
            $message->message,
            $subscriptions
        );

        $sms = new SMS_Api();
        $sms->submitMulti(
            'RUZGARNET',
            $messages
        );

        $this->info('Added payment penalty warning messages.');
    }
}
