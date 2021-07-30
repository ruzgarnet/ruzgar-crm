<?php

namespace App\Console\Commands;

use App\Classes\Messages;
use App\Classes\SMS_Api;
use App\Models\Customer;
use App\Models\Message;
use App\Models\SentMessage;
use Illuminate\Console\Command;

class MessageQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ruzgarnet:checkMessages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check messages in queue.';

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
        $messages = new Messages();

        $sms_list = SentMessage::where('type', 1)->get();
        $sms_api = new SMS_Api();
        $today = date('Y-m-d H:i');
        $sms_ids = [];

        $full_name = "";
        $phone = "";
        $text = "";

        $parameters = [];

        foreach ($sms_list as $sms) {
            $customer = Customer::find($sms->customer_id);
            $message = Message::find($sms->message_id);

            if ($customer) {
                $full_name = $customer->full_name;
                $phone = $customer->telephone;

                $parameters["referans_kodu"] = $customer->reference_code;
            } else {
                $full_name = $sms->full_name;
                $phone = $sms->phone;
            }

            $parameters["ad_soyad"] = $full_name;

            if ($message && !$sms->message) {
                $text = $message->message;
            } else {
                $text = $sms->message;
            }

            if (strlen($phone) < 10) {
                array_push($sms_ids, $sms->id);
                continue;
            }

            if ($sms->delivery_date <= $today) {
                $result = $sms_api->submit(
                    "RUZGARNET",
                    $messages->generate(
                        $text,
                        $parameters
                    ),
                    array($phone)
                );

                if ($result) {
                    array_push($sms_ids, $sms->id);
                }
            } else {
                $result = $sms_api->submit_in_time(
                    "RUZGARNET",
                    $messages->generate(
                        $text,
                        $parameters
                    ),
                    array($phone),
                    $sms->delivery_date
                );

                if ($result) {
                    array_push($sms_ids, $sms->id);
                }
            }
        }

        if (count($sms_ids) > 0) {
            if ($result) {
                SentMessage::whereIn("id", $sms_ids)->update([
                    "type" => 2
                ]);
            }
        }

        $this->info('Checked message queue.');
    }
}
