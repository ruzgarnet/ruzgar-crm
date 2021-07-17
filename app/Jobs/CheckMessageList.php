<?php

namespace App\Jobs;

use App\Classes\Messages;
use App\Classes\SMS_Api;
use App\Models\Customer;
use App\Models\Message;
use App\Models\SentMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CheckMessageList implements ShouldQueue
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

            if($customer)
            {
                $full_name = $customer->full_name;
                $phone = $customer->telephone;

                $parameters["referans_kodu"] = $customer->reference_code;
            }
            else
            {
                $full_name = $sms->full_name;
                $phone = $sms->phone;
            }

            $parameters["ad_soyad"] = $full_name;

            if($message && !$sms->message)
            {
                $text = $message->message;
            }
            else
            {
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
    }
}
