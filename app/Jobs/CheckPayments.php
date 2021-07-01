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

class CheckPayments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $messages = [];
        $subscriptions = Subscription::join('payments', 'subscriptions.id', 'payments.subscription_id')
            ->whereNotNull('subscriptions.approved_at')
            ->whereIn('subscriptions.status', [1, 2, 4])
            ->whereRaw('(TIMESTAMPDIFF(MONTH, `approved_at`, NOW()) >= 1 OR DAYOFMONTH(`approved_at`) < 25)')
            ->where('payments.date', date('Y-m-15'))
            ->get();

        $message = Message::find(2);

        $messages = (new Messages())->multiMessage(
            $message->message,
            $subscriptions
        );

        $sms = new SMS_Api();
        $sms->submitMulti(
            'RUZGARNET',
            $messages
        );
    }
}
