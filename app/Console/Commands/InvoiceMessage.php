<?php

namespace App\Console\Commands;

use App\Classes\Messages;
use App\Classes\SMS_Api;
use App\Models\Message;
use App\Models\Subscription;
use Illuminate\Console\Command;

class InvoiceMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ruzgarnet:invoiceMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add invoice messages.';

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
     * @return int
     */
    public function handle()
    {
        $messages = [];
        $subscriptions = Subscription::whereNotNull('subscriptions.approved_at')
            ->whereIn('subscriptions.status', [1, 4])
            ->whereRaw('(TIMESTAMPDIFF(MONTH, `approved_at`, NOW()) >= 1 OR DAYOFMONTH(`approved_at`) < 25)')
            ->whereRaw('id IN (SELECT subscription_id FROM payments WHERE `date` = \'' . date('Y-m-15') . '\')')
            ->get();

        $message = Message::find(37);
        $messages = new Messages();

        $message = $messages->generate(
            $message->message,
            [
                'ay' => date('m'),
                'yil' => date('Y')
            ]
        );

        $messages = $messages->multiMessage(
            $message,
            $subscriptions
        );

        $sms = new SMS_Api();
        $sms->submitMulti(
            'RUZGARNET',
            $messages
        );

        $this->info('Added invoice messages.');
    }
}
