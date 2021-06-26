<?php

namespace App\Jobs;

use App\Classes\Messages;
use App\Classes\SMS_Api;
use App\Models\Customer;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CheckSMSList implements ShouldQueue
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
        $sms_list = DB::table('sms_list')->get();
        $sms_api = new SMS_Api();
        $today = date('Y-m-d H:i');
        $sms_ids = [];

        foreach ($sms_list as $sms) {
            $customer = Customer::find($sms->customer_id);
            $message = Message::find($sms->message_id);

            if (strlen($customer->telephone) < 10) {
                array_push($sms_ids, $sms->id);
                continue;
            }

            if ($sms->delivery_date <= $today) {
                $result = $sms_api->submit(
                    "RUZGARNET",
                    $message->message,
                    array($customer->telephone)
                );

                if ($result) {
                    array_push($sms_ids, $sms->id);
                }
            } else {
                $result = $sms_api->submit_in_time(
                    "RUZGARNET",
                    $message->message,
                    array($customer->telephone),
                    $sms->delivery_date
                );

                if ($result) {
                    array_push($sms_ids, $sms->id);
                }
            }
        }

        if (count($sms_ids) > 0) {
            if ($result) {
                DB::table('sms_list')->whereIn("id", $sms_ids)->update([
                    "is_sent" => 1
                ]);
            }
        }
    }
}
