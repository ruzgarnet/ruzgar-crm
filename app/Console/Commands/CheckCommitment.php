<?php

namespace App\Console\Commands;

use App\Classes\Messages;
use App\Classes\SMS_Api;
use App\Classes\Telegram;
use App\Models\Message;
use App\Models\Subscription;
use App\Models\SubscriptionRenewal;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckCommitment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ruzgarnet:checkCommitment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check subscription\'s commitment date.';

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
        try {
            $sms = new SMS_Api();
            $message_formatter = new Messages();

            $message_45 = Message::find(40)->message;
            $message_30 = Message::find(41)->message;
            $message_15 = Message::find(42)->message;
            $message_renewal = Message::find(43)->message;

            $messages = [];

            $subscriptions = Subscription::where('status', 1)->whereRaw('DATEDIFF(`end_date`, NOW()) = 45')->get();
            foreach ($subscriptions as $subscription) {
                $messages[] = [
                    $subscription->customer->telephone,
                    $message_formatter->generate($message_45, [
                        'full_name' => $subscription->customer->full_name,
                        'subscription' => $subscription->service->name
                    ])
                ];

                Telegram::send(
                    'SözleşmesiSonaErecekler',
                    trans('telegram.subscription_ending_day', [
                        'full_name' => $subscription->customer->full_name,
                        'id_no' => $subscription->customer->identification_number,
                        'subscription' => $subscription->service->name,
                        'day' => '45'
                    ])
                );
            }

            $subscriptions = Subscription::where('status', 1)->whereRaw('DATEDIFF(`end_date`, NOW()) = 30')->get();
            foreach ($subscriptions as $subscription) {
                $messages[] = [
                    $subscription->customer->telephone,
                    $message_formatter->generate($message_30, [
                        'full_name' => $subscription->customer->full_name,
                        'subscription' => $subscription->service->name
                    ])
                ];

                Telegram::send(
                    'SözleşmesiSonaErecekler',
                    trans('telegram.subscription_ending_day', [
                        'full_name' => $subscription->customer->full_name,
                        'id_no' => $subscription->customer->identification_number,
                        'subscription' => $subscription->service->name,
                        'day' => '30'
                    ])
                );
            }

            $subscriptions = Subscription::where('status', 1)->whereRaw('DATEDIFF(`end_date`, NOW()) = 15')->get();
            foreach ($subscriptions as $subscription) {
                $new_price = $subscription->price;
                if ($renewal = $subscription->renewal()) {
                    $new_price = $renewal->new_price;
                }

                $messages[] = [
                    $subscription->customer->telephone,
                    $message_formatter->generate($message_15, [
                        'full_name' => $subscription->customer->full_name,
                        'subscription' => $subscription->service->name,
                        'price' => $new_price
                    ])
                ];

                Telegram::send(
                    'SözleşmesiSonaErecekler',
                    trans('telegram.subscription_ending_day', [
                        'full_name' => $subscription->customer->full_name,
                        'id_no' => $subscription->customer->identification_number,
                        'subscription' => $subscription->service->name,
                        'day' => '15'
                    ])
                );
            }

            $subscriptions = Subscription::where('status', 1)->whereRaw('DATEDIFF(`end_date`, NOW()) = 1')->get();
            foreach ($subscriptions as $subscription) {
                $new_price = $subscription->price;
                if ($renewal = $subscription->renewal()) {
                    $new_price = $renewal->new_price;
                    $renewal->status = 1;
                    $renewal->save();
                } else {
                    SubscriptionRenewal::create([
                        'subscription_id' => $subscription->id,
                        'new_price' => $subscription->price,
                        'status' => 1
                    ]);
                }

                $subscription->end_date = Carbon::parse($subscription->end_date)->addMonth($subscription->commitment)->toDateString();
                $subscription->price = $new_price;
                $subscription->save();

                $messages[] = [
                    $subscription->customer->telephone,
                    $message_formatter->generate($message_renewal, [
                        'full_name' => $subscription->customer->full_name,
                        'subscription' => $subscription->service->name,
                        'price' => $new_price
                    ])
                ];

                Telegram::send(
                    'SözleşmesiSonaErecekler',
                    trans('telegram.subscription_renewaled', [
                        'full_name' => $subscription->customer->full_name,
                        'id_no' => $subscription->customer->identification_number,
                        'subscription' => $subscription->service->name,
                        'month' => $subscription->commitment,
                        'price' => $new_price
                    ])
                );
            }

            $sms->submitMulti(
                "RUZGARNET",
                $messages
            );
        } catch (Exception $e) {
            Telegram::send('Test', 'CheckCommitment Command - ' . $e->getMessage());
        }
    }
}
