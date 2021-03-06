<?php

namespace App\Models\Generators;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Generate payment datas for subscription model
 */
trait SubscriptionPaymentGenerator
{
    /**
     * Generates first payments
     *
     * @return array
     */
    public function generatePayments()
    {
        $data = [];
        $values = [];

        if ($this->options) {
            foreach ($this->options as $key => $value) {
                if (method_exists($this, $key)) {
                    $row = $this->{$key}($value);
                    $data[] = $row;

                    if (array_key_exists('payment', $row)) {
                        $values[$key] = $row['payment'];
                    }
                    if (array_key_exists('price', $row)) {
                        $values[$key] = $row['price'];
                    }
                    if (array_key_exists('payments', $row)) {
                        $total = 0;
                        foreach ($row['payments'] as $payment) {
                            $total += $payment;
                        }
                        $values[$key] = $total;
                    }
                }
            }

            if ($service_option = $this->service->options) {
                $data[] = $this->serviceOption();
                $values["campaign_price"] = $service_option["price"];
                $values["duration"] = $service_option["duration"];

                if (in_array($this->service_id, [37, 38, 40, 41])) {
                    $plaka = $this->customer->customerInfo->city->plaka;
                    $values["campaign_price"] = $plaka;
                    if ($plaka > 50) {
                        $values["campaign_price"] = floor($plaka / 10) + ($plaka % 10);
                    }
                }
            }

            $values['service_price'] = $this->service->price;
            if (in_array($this->commitment, [0, 12])) {
                $values['service_price'] += 10;
            }

            $this->values = $values;

            $data = collect($data);

            $this->payment = $data->sum("payment");
            $this->price += $data->sum("price");

            $payment_variables = $data->filter(function ($value) {
                return in_array("payments", array_keys($value));
            })->pluck("payments");

            $months = $payment_variables->map(function ($item) {
                return count($item);
            })->max();

            $date_append = 1;

            if ($this->getOption('pre_payment')) {
                if ($months < 2) {
                    $months = 2;
                }
                $date_append = 0;
            }

            if ($months == 0) {
                $months = 1;
            }

            $payments = [];
            for ($i = 0; $i < $months; $i++) {
                $payments[$i]["price"] = (float)$this->price;
            }

            foreach ($payment_variables->toArray() as $values) {
                foreach ($values as $index => $value) {
                    $payments[$index]["price"] += (float)$value;
                }
            }

            foreach ($payments as $index => $value) {
                $payments[$index]["subscription_id"] = $this->id;
                $payments[$index]["date"] = Carbon::now()->startOfMonth()->addMonth($date_append)->format('Y-m-15');
                $date_append++;
            }

            if ($this->getOption('pre_payment')) {
                $payments[0]['paid_at'] = DB::raw('current_timestamp()');
                $payments[0]['type'] = 6;
                $payments[0]['status'] = 2;
            }
        }

        return $payments ?? [];
    }

    /**
     * Setup payment variables
     *
     * @param int $value
     * @return array
     */
    public function setup_payment($value)
    {
        $payment = (float)setting('service.setup.payment');

        if ($value == 0) {
            return [];
        }

        if ($value == 1) {
            return ['payment' => $payment];
        }

        $data = [];
        for ($i = 0; $i < $value - 1; $i++) {
            $data[] = $payment / ($value - 1);
        }

        return [
            'payments' => $data
        ];
    }

    /**
     * Modem payment variables
     *
     * @param int $value
     * @return array
     */
    public function modem_payment($value)
    {
        // 1 => yok
        // 2 => adsl
        // 3 => vdsl
        // 4 => fiber
        // 5 => uydu modem

        $type = (int)$this->getOption('modem');

        $typeVal = '';
        if ($type == 2) {
            $typeVal = 'adsl';
        } else if ($type == 3) {
            $typeVal = 'vdsl';
        }

        $payment = (float)setting("service.modem.{$typeVal}");

        if ($value == 1) {
            return ['payment' => $payment];
        }

        $data = [];
        for ($i = 0; $i < $value - 1; $i++) {
            $data[] = $payment / ($value - 1);
        }

        return [
            'payments' => $data
        ];
    }

    /**
     * Modem Price Variables
     *
     * @return array
     */
    public function modem_price()
    {
        return [
            'price' => (float)$this->getOption('modem_price', 0)
        ];
    }

    public function serviceOption()
    {
        $options = $this->service->options;
        if (in_array($this->service_id, [37, 38, 40, 41])) {
            $plaka = $this->customer->customerInfo->city->plaka;
            $options['price'] = $plaka;
            if ($plaka > 50) {
                $options['price'] = floor($plaka / 10) + ($plaka % 10);
            }
        }
        $data = [];
        if ($this->commitment != null && isset($options["commitment"])) {
            if ($this->commitment == $options["commitment"]) {
                $price = -1 * ($this->price - $options["price"]);

                for ($iteration = 0; $iteration < $options["duration"]; $iteration++) {
                    $data[] = $price;
                }
            }
        }

        if ($data)
            return [
                'payments' => $data
            ];
        return [];
    }
}
