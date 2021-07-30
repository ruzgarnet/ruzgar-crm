<?php

namespace App\Console\Commands;

use App\Classes\SMS_Api;
use App\Models\Customer;
use App\Models\Message;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ruzgarnet:sendSMS';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS.';

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

        $this->info('Sended SMS.');
    }
}
