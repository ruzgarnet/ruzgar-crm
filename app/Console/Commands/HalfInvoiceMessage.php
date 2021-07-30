<?php

namespace App\Console\Commands;

use App\Classes\Messages;
use App\Classes\SMS_Api;
use App\Models\Message;
use App\Models\Subscription;
use Illuminate\Console\Command;

class HalfInvoiceMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ruzgarnet:halfInvoiceMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check newcomers for late invoice message.';

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
        $subscriptions = Subscription::join('payments', 'subscriptions.id', 'payments.subscription_id')
            ->whereNotNull('subscriptions.approved_at')
            ->whereIn('subscriptions.status', [1, 4])
            ->whereRaw('(TIMESTAMPDIFF(MONTH, `subscriptions`.`approved_at`, NOW()) < 1 AND DAYOFMONTH(`subscriptions`.`approved_at`) >= 25)')
            ->where('payments.date', date('Y-m-15'))
            ->get();

        $message = Message::find(37);

        $messages = (new Messages())->multiMessage(
            $message->message,
            $subscriptions
        );

        $sms = new SMS_Api();
        $sms->submitMulti(
            'RUZGARNET',
            $messages
        );

        $this->info('Added invoice messages for newcomers.');
    }
}
