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

class CheckPenalties implements ShouldQueue
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

        $message = Message::find(10);

        $subscriptions = Subscription::join('payments', 'subscriptions.id', 'payments.subscription_id')->where("payments.status", "<>", 2)->where('payments.date', date('Y-m-15'))->get();

        $messages = (new Messages())->multiMessage(
            $message->message,
            $subscriptions
        );

        dd($messages);
        // $sms = new SMS_Api();
        // $sms->submitMulti(
        //     'RUZGARNET',
        //     $messages
        // );
    }
}
